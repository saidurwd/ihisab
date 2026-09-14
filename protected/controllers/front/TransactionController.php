<?php

class TransactionController extends Controller {

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
                'actions' => array('*'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'single', 'multiple', 'update', 'admin', 'delete', 'tag', 'account', 'fixtag'),
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

    public function actionFixtag() {
        set_time_limit(0);
        $array = Transaction::model()->findAll(array('condition' => 'tag IS NOT NULL OR tag !=""'));
        foreach ($array as $key => $value) {
            $ex = explode(',', $value['tag']);
            $total = count($ex);
            for ($i = 0; $i < $total; $i++) {
                $model = new TransactionTag;
                $model->transaction = $value['id'];
                $model->tag = $ex[$i];
                $model->save();
            }
        }
        Yii::app()->user->setFlash('success', 'Transaction tag transffered successfully.');
        $this->redirect(array('admin'));
    }

    public function actionCreate() {
        $model = new Transaction;
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        Transaction::checkUser($id);
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionSingle() {
        $this->layout = false;
        //$this->layout = '//layouts/popup';
        $model = new Transaction;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Transaction'])) {
                $model->attributes = $_POST['Transaction'];
                $model->user = Yii::app()->user->id;
                $model->amount = str_replace(",", "", $model->amount);
                if (!empty($model->attributes['tag'])) {
                    $model->tag = implode(",", (array) $model->attributes['tag']);
                }
                if ($model->attributes['transaction_type'] == 3) {
                    $accounts = $model->account . ',' . $model->AccountTo;
                    $model->account = implode(",", (array) $accounts);
                }
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Transaction was saved successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('single', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Transaction'])) {
            $model->attributes = $_POST['Transaction'];
            $model->user = Yii::app()->user->id;
            $model->amount = str_replace(",", "", $model->amount);
            if (!empty($model->attributes['tag'])) {
                $model->tag = implode(",", (array) $model->attributes['tag']);
            }
            if ($model->attributes['transaction_type'] == 3) {
                $accounts = $model->account . ',' . $model->AccountTo;
                $model->account = implode(",", (array) $accounts);
            }
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Transaction was saved successfully.');
                $this->redirect(array('admin'));
            }
        }

        $this->render('single', array(
            'model' => $model,
        ));
    }

    public function actionMultiple() {
        $this->layout = false;
        $model = new Transaction;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Transaction'])) {
                $model = new Transaction;
                for ($i = 1; $i <= 10; $i++) {
                    $model->attributes = $_POST['Transaction'][$i];
                    if (!empty($model->description) && !empty($model->amount) && !empty($model->account)) {
                        if (!empty($model->attributes['tag'])) {
                            $model->tag = implode(",", (array) $model->attributes['tag']);
                        }
                        $ex = explode(',', $model->tag);
                        $total = count($ex);
                        $model->user = Yii::app()->user->id;
                        $model->amount = str_replace(",", "", $model->amount);
//                        $model->save();
                        if ($model->save()) {
                            for ($k = 0; $k < $total; $k++) {
                                if (($tag = TransactionTag::model()->find(array('condition' => 'transaction=' . (int) $model->id . ' AND tag=' . (int) $ex[$k]))) === null)
                                    $tag = new TransactionTag;
                                $tag->transaction = $model->id;
                                $tag->tag = $ex[$k];
                                $tag->save();
                            }
                        }
                        $model = new Transaction;
                    }
                }
                Yii::app()->user->setFlash('success', 'Transactions was saved successfully.');
                Yii::app()->end();
            }
        }

        if (isset($_POST['Transaction'])) {
            $model = new Transaction;
            for ($i = 1; $i <= 10; $i++) {
                $model->attributes = $_POST['Transaction'][$i];
                if (!empty($model->description) && !empty($model->amount) && !empty($model->account)) {
                    if (!empty($model->attributes['tag'])) {
                        $model->tag = implode(",", (array) $model->attributes['tag']);
                    }
                    $ex = explode(',', $model->tag);
                    $total = count($ex);
                    $model->user = Yii::app()->user->id;
                    $model->amount = str_replace(",", "", $model->amount);
//                    $model->save();
                    if ($model->save()) {
                        for ($k = 0; $k < $total; $k++) {
                            if (($tag = TransactionTag::model()->find(array('condition' => 'transaction=' . (int) $model->id . ' AND tag=' . (int) $ex[$k]))) === null)
                                $tag = new TransactionTag;
                            $tag->transaction = $model->id;
                            $tag->tag = $ex[$k];
                            $tag->save();
                        }
                    }
                    $model = new Transaction;
                }
            }
            Yii::app()->user->setFlash('success', 'Transactions was saved successfully.');
            $this->redirect(array('admin'));
        }

        $this->render('multiple', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        Transaction::checkUser($id);
        $this->layout = false;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Transaction'])) {
                $model->attributes = $_POST['Transaction'];
                $model->modified = new CDbExpression('NOW()');
                if (!empty($model->attributes['tag'])) {
                    $model->tag = implode(",", (array) $model->attributes['tag']);
                }
                $ex = explode(',', $model->tag);
                $total = count($ex);
                if ($model->attributes['transaction_type'] == 3) {
                    $accounts = $model->account . ',' . $model->AccountTo;
                    $model->account = implode(",", (array) $accounts);
                }
                if ($model->save()) {
                    //remove users from previous access
                    TransactionTag::model()->deleteAll('transaction=' . $model->id . ' AND tag NOT IN(' . $model->tag . ')');
                    //insert/update users from previous access
                    for ($k = 0; $k < $total; $k++) {
                        if (($tag = TransactionTag::model()->find(array('condition' => 'transaction=' . (int) $model->id . ' AND tag=' . (int) $ex[$k]))) === null)
                            $tag = new TransactionTag;
                        $tag->transaction = $model->id;
                        $tag->tag = $ex[$k];
                        $tag->save();
                    }
                    Yii::app()->user->setFlash('success', 'Transactions was saved successfully.');
                    Yii::app()->end();
                }
            }
            if (isset($model->tag)) {
                $model->tag = explode(',', $model->tag);
            }
            echo $this->renderPartial('update', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Transaction'])) {
            $model->attributes = $_POST['Transaction'];
            $model->modified = new CDbExpression('NOW()');
            if (!empty($model->attributes['tag'])) {
                $model->tag = implode(",", (array) $model->attributes['tag']);
            }
            $ex = explode(',', $model->tag);
            $total = count($ex);
            if ($model->attributes['transaction_type'] == 3) {
                $accounts = $model->account . ',' . $model->AccountTo;
                $model->account = implode(",", (array) $accounts);
            }
            if ($model->save()) {
                //remove users from previous access
                TransactionTag::model()->deleteAll('transaction=' . $model->id . ' AND tag NOT IN(' . $model->tag . ')');
                //insert/update users from previous access
                for ($k = 0; $k < $total; $k++) {
                    if (($tag = TransactionTag::model()->find(array('condition' => 'transaction=' . (int) $model->id . ' AND tag=' . (int) $ex[$k]))) === null)
                        $tag = new TransactionTag;
                    $tag->transaction = $model->id;
                    $tag->tag = $ex[$k];
                    $tag->save();
                }
                Yii::app()->user->setFlash('success', 'Transactions was saved successfully.');
                $this->redirect(array('admin'));
            }
        }

        if (isset($model->tag)) {
            $model->tag = explode(',', $model->tag);
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
        Transaction::checkUser($id);
        TransactionTag::model()->deleteAll('transaction=' . (int) $id);
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Transaction');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Transaction('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction']))
            $model->attributes = $_GET['Transaction'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Transaction by TAG
     */
    public function actionTag() {
        Tag::checkUser($_GET['id']);
        $model = new Transaction('search_tag');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction']))
            $model->attributes = $_GET['Transaction'];

        $this->render('tag', array(
            'model' => $model,
        ));
    }

    /**
     * Transaction by ACCOUNT
     */
    public function actionAccount() {
        Account::checkUser($_GET['id']);
        $model = new Transaction('search_account');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction']))
            $model->attributes = $_GET['Transaction'];

        $this->render('account', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Transaction the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Transaction::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Transaction $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'transaction-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
