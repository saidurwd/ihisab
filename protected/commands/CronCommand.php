<?php

// php /home/path/protected/yiic.php cron
class CronCommand extends CConsoleCommand {

    /**
     * Send mail method
     */
    public static function sendMail($email, $subject, $message, $fromName, $fromMail) {
        $adminEmail = 'Optimosolution<' . $fromMail . '>';
        $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

    public function get_subscriber_name($id) {
        $value = Yii::app()->db->createCommand()
                ->select('name')
                ->from('{{user}}')
                ->where('id=' . $id)
                ->queryScalar();
        return $value;
    }

    public function get_subscriber_email($id) {
        $value = Yii::app()->db->createCommand()
                ->select('email')
                ->from('{{user}}')
                ->where('id=' . $id)
                ->queryScalar();
        return $value;
    }

    public function actionIndex() {
        $subject = 'Test mail';
        $fromNames = Yii::app()->params['adminName'];
        $fromMails = Yii::app()->params['adminEmail'];
        $to_name = $this->get_subscriber_name($id);
        $to_mail = $this->get_subscriber_email($id);
        $recipients = "{$to_name}<{$to_mail}>";
        $this->sendMail($recipients, $subject, $body, $fromNames, $fromMails);
    }

    /**
     * Send daily transaction summary emails
     * Usage: php yiic.php cron dailySummary
     */
    public function actionDailySummary() {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $service = new DailySummaryService();
        $mailer = Yii::app()->mailer;

        $users = User::model()->findAll();
        foreach ($users as $user) {
            $pref = EmailPreference::getPreference($user->id);
            if (!$pref->enabled || $pref->frequency !== 'daily') {
                continue;
            }

            if ($pref->last_sent_at && date('Y-m-d', strtotime($pref->last_sent_at)) === date('Y-m-d')) {
                continue;
            }

            $summary = $service->buildSummary($user->id, $yesterday);
            if ($summary === null) {
                $log = new EmailLog();
                $log->user_id = $user->id;
                $log->email = $user->email;
                $log->subject = 'Daily Transaction Summary - ' . date('M d, Y', strtotime($yesterday));
                $log->status = 'skipped';
                $log->save();
                continue;
            }

            $data = array(
                'user' => $user,
                'summary' => $summary,
                'date' => $yesterday,
            );
            $viewFile = Yii::getPathOfAlias('application.views.email.dailySummary') . '.php';
            $rendered = $this->renderViewFile($viewFile, $data);

            $subject = 'Daily Transaction Summary - ' . date('M d, Y', strtotime($yesterday));
            $sent = $mailer->sendEmail($user->email, $subject, $rendered);

            $log = new EmailLog();
            $log->user_id = $user->id;
            $log->email = $user->email;
            $log->subject = $subject;
            $log->status = $sent ? 'sent' : 'failed';
            $log->save();

            if ($sent) {
                $pref->last_sent_at = date('Y-m-d H:i:s');
                $pref->save();
            }
        }

        echo "Daily summary job completed.\n";
    }

    /**
     * Send a single test email to a user
     * Usage: php yiic.php cron testEmail userId
     */
    public function actionTestEmail($userId) {
        $user = User::model()->findByPk($userId);
        if (!$user) {
            echo "User {$userId} not found.\n";
            return;
        }

        $service = new DailySummaryService();
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $summary = $service->buildSummary($user->id, $yesterday);
        if ($summary === null) {
            $summary = array(
                'date' => $yesterday,
                'total_count' => 0,
                'total_income' => 0,
                'total_expense' => 0,
                'net' => 0,
                'expense_by_tag' => array(),
                'income_by_tag' => array(),
            );
        }

        $data = array(
            'user' => $user,
            'summary' => $summary,
            'date' => $yesterday,
        );
        $viewFile = Yii::getPathOfAlias('application.views.email.dailySummary') . '.php';
        $rendered = $this->renderViewFile($viewFile, $data);

        $subject = 'Test Email - Daily Transaction Summary';
        $sent = Yii::app()->mailer->sendEmail($user->email, $subject, $rendered);

        $log = new EmailLog();
        $log->user_id = $user->id;
        $log->email = $user->email;
        $log->subject = $subject;
        $log->status = $sent ? 'sent' : 'failed';
        $log->save();

        echo $sent ? "Test email sent to {$user->email}\n" : "Test email failed for {$user->email}\n";
    }

    protected function renderViewFile($viewFile, $data)
    {
        extract($data, EXTR_SKIP);
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }

}
