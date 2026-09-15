<?php

class SiteController extends Controller
{

    public $layout = '//layouts/column2';

    public function afterAction($action)
    {
        self::keepAlive();
        parent::afterAction($action);
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('view', 'contact', 'login', 'captcha'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'create', 'update', 'admin', 'delete', 'logout'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'logout'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Yii::app()->clientScript->registerMetaTag("iHisab, Hisab, money management, personal finance, budgeting, shared bills, shared expense, roommates, reminder, forecasting", 'keywords');
        //dashboard - tag expanse summary
        $model_dashboard_report = new Tag('dashboard_report');
        $model_dashboard_report->unsetAttributes();  // clear any default values
        if (isset($_GET['Tag']))
            $model_dashboard_report->attributes = $_GET['Tag'];

        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        $userId = Yii::app()->user->id;

        $treeData = Yii::app()->cache->get('dashboard_tree_' . $userId . '_' . $month . '_' . $year);
        if ($treeData === false) {
            $treeData = Tag::getHierarchicalReport($month, $year);
            Yii::app()->cache->set('dashboard_tree_' . $userId . '_' . $month . '_' . $year, $treeData, 300);
        }

        $yearlyData = Yii::app()->cache->get('dashboard_yearly_' . $userId);
        if ($yearlyData === false) {
            $yearlyData = Transaction::getYearlyIncomeExpanse();
            Yii::app()->cache->set('dashboard_yearly_' . $userId, $yearlyData, 1800);
        }

        $accounts = Yii::app()->cache->get('dashboard_accounts_' . $userId);
        if ($accounts === false) {
            $accounts = Account::model()->findAll(array(
                'condition' => 'user=' . $userId,
            ));
            Yii::app()->cache->set('dashboard_accounts_' . $userId, $accounts, 600);
        }

        $recentTransactions = Yii::app()->cache->get('dashboard_recent_' . $userId);
        if ($recentTransactions === false) {
            $recentTransactions = Transaction::model()->findAll(array(
                'condition' => 'user=' . $userId,
                'order' => 'created DESC',
                'limit' => 10,
            ));
            Yii::app()->cache->set('dashboard_recent_' . $userId, $recentTransactions, 300);
        }

        $monthlyTrendLabels = Account::last_twelve_months();
        $monthlyTrendCacheKey = 'dashboard_monthly_trend_' . $userId;
        $monthlyTrend = Yii::app()->cache->get($monthlyTrendCacheKey);
        if ($monthlyTrend === false) {
            $monthlyTrendIncome = array();
            $monthlyTrendExpense = array();
            for ($t = 0; $t < 12; $t++) {
                $date = date('Y-m-t', strtotime(date('Y-m-01') . " -$t months"));
                $monthlyTrendIncome[] = (float)Transaction::get_income_specific_month($date);
                $monthlyTrendExpense[] = (float)Transaction::get_expense_specific_month($date);
            }
            $monthlyTrend = array(
                'income' => array_reverse($monthlyTrendIncome),
                'expense' => array_reverse($monthlyTrendExpense),
            );
            Yii::app()->cache->set($monthlyTrendCacheKey, $monthlyTrend, 900);
        }
        $monthlyTrendIncome = $monthlyTrend['income'];
        $monthlyTrendExpense = $monthlyTrend['expense'];

        $expenseChartData = Yii::app()->cache->get('dashboard_expense_chart_' . $userId);
        if ($expenseChartData === false) {
            $expenseChartData = Transaction::dashboardExpenseChart();
            Yii::app()->cache->set('dashboard_expense_chart_' . $userId, $expenseChartData, 600);
        }

        $incomeChartData = Yii::app()->cache->get('dashboard_income_chart_' . $userId);
        if ($incomeChartData === false) {
            $incomeChartData = Transaction::dashboardIncomeChart();
            Yii::app()->cache->set('dashboard_income_chart_' . $userId, $incomeChartData, 600);
        }

        $balanceChartData = Yii::app()->cache->get('dashboard_balance_chart_' . $userId);
        if ($balanceChartData === false) {
            $balanceChartData = Transaction::accountBalanceChart();
            Yii::app()->cache->set('dashboard_balance_chart_' . $userId, $balanceChartData, 900);
        }

        $this->render('index', array(
            'model_dashboard_report' => $model_dashboard_report,
            'treeData' => $treeData,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'yearlyData' => $yearlyData,
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'monthlyTrendLabels' => $monthlyTrendLabels,
            'monthlyTrendIncome' => $monthlyTrendIncome,
            'monthlyTrendExpense' => $monthlyTrendExpense,
            'expenseChartData' => $expenseChartData,
            'incomeChartData' => $incomeChartData,
            'balanceChartData' => $balanceChartData,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidationContact($model, "contact-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['ContactForm'])) {
                $model->attributes = $_POST['ContactForm'];
                if ($model->validate()) {
                    $subject = $model->subject;
                    $message = $_POST['ContactForm']['body'];
                    $fromName = $model->name;
                    $fromMail = $model->email;
                    $recipient = Yii::app()->params['adminEmail'];
                    $this->sendMail($recipient, $subject, $message, $fromName, $fromMail);

                    //sending mail to mail sender
                    $recipients = $model->email;
                    $subjects = 'Acknowledgement to your enquiry';
                    $messages = 'Dear ' . $model->name . ',<br /><br />';
                    $messages .= 'Thank you very much for contacting ' . Yii::app()->params['adminName'] . '. We will be shortly contacting you with necessary update. ' . Yii::app()->params['adminName'] . ' team is working to ensure your satisfaction to our service. <br /><br />Sincere regards,<br />Customer Support Team, ' . Yii::app()->params['adminName'] . '<br />optimosolution@gmail.com<br />www.ihisab.com';
                    $fromNames = Yii::app()->params['name'];
                    $fromMails = Yii::app()->params['adminEmail'];
                    $this->sendMail($recipients, $subjects, $messages, $fromNames, $fromMails);

                    Yii::app()->user->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                    echo $model->email;
                    return;
                }
            }
        } else {
            if (isset($_POST['ContactForm'])) {
                $model->attributes = $_POST['ContactForm'];
                if ($model->validate()) {
                    $subject = $model->subject;
                    $message = $_POST['ContactForm']['body'];
                    $fromName = $model->name;
                    $fromMail = $model->email;
                    $recipient = Yii::app()->params['adminEmail'];
                    $this->sendMail($recipient, $subject, $message, $fromName, $fromMail);

                    //sending mail to mail sender
                    $recipients = $model->email;
                    $subjects = 'Acknowledgement to your enquiry';
                    $messages = 'Dear ' . $model->name . ',<br /><br />';
                    $messages .= 'Thank you very much for contacting ' . Yii::app()->params['adminName'] . '. We will be shortly contacting you with necessary update. ' . Yii::app()->params['adminName'] . ' team is working to ensure your satisfaction to our service. <br /><br />Sincere regards,<br />Customer Support Team, ' . Yii::app()->params['adminName'] . '<br />optimosolution@gmail.com<br />www.ihisab.com';
                    $fromNames = Yii::app()->params['name'];
                    $fromMails = Yii::app()->params['adminEmail'];
                    $this->sendMail($recipients, $subjects, $messages, $fromNames, $fromMails);

                    Yii::app()->user->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                    Yii::app()->end();
                }
            }
        }
    }

    /**
     * Send mail method
     */
    public function sendMail($email, $subject, $message, $fromName, $fromMail)
    {
        $adminEmail = Yii::app()->params['adminName'] . '-' . $fromName . '<' . $fromMail . '>';
        $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

    protected function performAjaxValidationContact($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'contact-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        Yii::app()->clientScript->registerMetaTag("iHisab, Hisab, money management, personal finance, budgeting, shared bills, shared expense, roommates, reminder, forecasting", 'keywords');

        $this->layout = '//layouts/login';
        if (@Yii::app()->user->id) {
//            $this->redirect(Yii::app()->homeUrl);
            $this->redirect(array('/transaction/admin'));
        }
        $model = new LoginFormUser;
        $registration = new User;
        $contact = new ContactForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginFormUser'])) {
            $model->attributes = $_POST['LoginFormUser'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $audit = new AuditTrail;
                $audit->user_id = Yii::app()->user->id;
                $audit->user_type = 0;
                $audit->login_time = new CDbExpression('NOW()');
                $audit->save();
                Yii::app()->user->setFlash('success', 'Welcome to the wonderful world of <strong>' . CHtml::encode(Yii::app()->name) . '</strong>. With advanced features you will definitely have a great experience of using <strong>' . CHtml::encode(Yii::app()->name) . '</strong>.');
                $this->redirect(Yii::app()->user->returnUrl);
//                $this->redirect(array('/transaction/admin'));
            }
        }
        // display the login form
        $this->render('login', array(
            'model' => $model,
            'registration' => $registration,
            'contact' => $contact,
        ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        if (@Yii::app()->user->id) {
            Yii::app()->db->createCommand('UPDATE {{audit_trail}} SET `logout_time` = NOW() WHERE user_id=' . Yii::app()->user->id . ' ORDER BY login_time DESC LIMIT 1')->execute();
        }

        Yii::app()->user->logout();
        Yii::app()->user->setFlash('success', 'Logout Successful! You can improve your security further after logging out by closing this opened browser.');
        $this->redirect(Yii::app()->homeUrl);
    }

}
