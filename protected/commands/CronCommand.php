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

            $rendered = Yii::app()->controller->renderPartial('//email/dailySummary', array(
                'user' => $user,
                'summary' => $summary,
                'date' => $yesterday,
            ), true);

            $sent = $this->sendMail(
                $user->email,
                'Daily Transaction Summary - ' . date('M d, Y', strtotime($yesterday)),
                $rendered,
                Yii::app()->params['adminName'],
                Yii::app()->params['adminEmail']
            );

            $log = new EmailLog();
            $log->user_id = $user->id;
            $log->email = $user->email;
            $log->subject = 'Daily Transaction Summary - ' . date('M d, Y', strtotime($yesterday));
            $log->status = $sent ? 'sent' : 'failed';
            $log->save();

            if ($sent) {
                $pref->last_sent_at = date('Y-m-d H:i:s');
                $pref->save();
            }
        }

        echo "Daily summary job completed.\n";
    }

}
