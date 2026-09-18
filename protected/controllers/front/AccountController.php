<?php

class AccountController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    public function afterAction($action) {
        self::keepAlive();
        parent::afterAction($action);
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array(),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'index', 'view'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        Account::checkUser($id);
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
//        if (!isset($_GET['ajax']))
//            $this->redirect(array('account/admin'));

        $this->layout = false;
        $model = new Account;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Account'])) {
                $model->attributes = $_POST['Account'];
                $model->created = new CDbExpression('NOW()');
                $model->user = Yii::app()->user->id;
                if ($model->save()) {
                    if ($model->default_account == 1) {
                        Account::model()->updateAll(array('default_account' => 0), 'user = ' . Yii::app()->user->id . ' AND id != ' . $model->id);
                    }
                    Yii::app()->user->setFlash('success', 'Account has been created successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('create', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            $model->created = new CDbExpression('NOW()');
            $model->user = Yii::app()->user->id;
            if ($model->save()) {
                if ($model->default_account == 1) {
                    Account::model()->updateAll(array('default_account' => 0), 'user = ' . Yii::app()->user->id . ' AND id != ' . $model->id);
                }
                Yii::app()->user->setFlash('success', 'Account has been created successfully.');
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
//        if (!isset($_GET['ajax']))
//            $this->redirect(array('account/admin'));

        Account::checkUser($id);
        $this->layout = false;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Account'])) {
                $model->attributes = $_POST['Account'];
                $model->modified = new CDbExpression('NOW()');
                if ($model->save()) {
                    if ($model->default_account == 1) {
                        Account::model()->updateAll(array('default_account' => 0), 'user = ' . Yii::app()->user->id . ' AND id != ' . $model->id);
                    }
                    Yii::app()->user->setFlash('success', 'Account has been updated successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('update', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            $model->modified = new CDbExpression('NOW()');
            if ($model->save()) {
                if ($model->default_account == 1) {
                    Account::model()->updateAll(array('default_account' => 0), 'user = ' . Yii::app()->user->id . ' AND id != ' . $model->id);
                }
                Yii::app()->user->setFlash('success', 'Account has been updated successfully.');
                $this->redirect(array('admin'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        Account::checkUser($id);
        //$this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Account');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Account('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Account']))
            $model->attributes = $_GET['Account'];

        $userId = Yii::app()->user->id;
        $accountTypes = Yii::app()->cache->get('account_filter_types');
        if ($accountTypes === false) {
            $accountTypes = AccountType::model()->findAll(array('condition' => 'status=1', 'order' => 'title'));
            Yii::app()->cache->set('account_filter_types', $accountTypes, 3600);
        }

        $currencies = Yii::app()->cache->get('account_filter_currencies');
        if ($currencies === false) {
            $currencies = Currency::model()->findAll(array('condition' => 'published=1', 'order' => 'currency_name'));
            Yii::app()->cache->set('account_filter_currencies', $currencies, 3600);
        }

        $balanceChartData = Yii::app()->cache->get('account_chart_balance_' . $userId);
        if ($balanceChartData === false) {
            $balanceChartData = Transaction::accountBalanceChart();
            Yii::app()->cache->set('account_chart_balance_' . $userId, $balanceChartData, 900);
        }

        $incomeComparisonData = Yii::app()->cache->get('account_chart_income_' . $userId);
        if ($incomeComparisonData === false) {
            $incomeComparisonData = Transaction::accountIncomeComparisonChart();
            Yii::app()->cache->set('account_chart_income_' . $userId, $incomeComparisonData, 900);
        }

        $expenseComparisonData = Yii::app()->cache->get('account_chart_expense_' . $userId);
        if ($expenseComparisonData === false) {
            $expenseComparisonData = Transaction::accountExpanceComparisonChart();
            Yii::app()->cache->set('account_chart_expense_' . $userId, $expenseComparisonData, 900);
        }

        $balanceComparisonData = Yii::app()->cache->get('account_chart_balance_comp_' . $userId);
        if ($balanceComparisonData === false) {
            $balanceComparisonData = Transaction::accountBalanceComparisonChart();
            Yii::app()->cache->set('account_chart_balance_comp_' . $userId, $balanceComparisonData, 900);
        }

        $worthComparisonData = Yii::app()->cache->get('account_chart_worth_' . $userId);
        if ($worthComparisonData === false) {
            $worthComparisonData = Transaction::accountWorthComparisonChart();
            Yii::app()->cache->set('account_chart_worth_' . $userId, $worthComparisonData, 900);
        }

        $this->render('admin', array(
            'model' => $model,
            'accountTypes' => $accountTypes,
            'currencies' => $currencies,
            'balanceChartData' => $balanceChartData,
            'incomeComparisonData' => $incomeComparisonData,
            'expenseComparisonData' => $expenseComparisonData,
            'balanceComparisonData' => $balanceComparisonData,
            'worthComparisonData' => $worthComparisonData,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Account the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Account::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Account $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'account-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
