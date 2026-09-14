<?php

class RecoveryController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/login';

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
                'actions' => array('recovery', 'changepassword'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Recovery password
     */
    public function actionRecovery() {
        $form = new UserRecoveryForm;
        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->homeUrl);
        } else {
            $email = ((isset($_GET['email'])) ? $_GET['email'] : '');
            $activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
            if ($email && $activkey) {
                $form2 = new Recovery;
                $find = Recovery::model()->findByAttributes(array('email' => $email));
                if (isset($find) && $find->activation == $activkey) {
                    if (isset($_POST['Recovery'])) {
                        $form2->attributes = $_POST['Recovery'];
                        if ($form2->validate()) {
                            $find->password = SHA1($form2->password);
                            $find->activation = md5(microtime());
                            Recovery::model()->updateAll(array('password' => $find->password, 'activkey' => $find->activation), 'email = "' . $email . '"');
                            Yii::app()->user->setFlash('success', 'Congratulation! New password has been saved!!');
                            $this->redirect(array('/site/login'));
                        }
                    }
                    $this->render('changepassword', array('form2' => $form2));
                } else {
                    Yii::app()->user->setFlash('error', 'Incorrect recovery link.');
                    $this->redirect(array('/recovery/recovery'));
                }
            } else {
                if (isset($_POST['UserRecoveryForm'])) {
                    $form->attributes = $_POST['UserRecoveryForm'];
                    if ($form->validate()) {
                        $user = Recovery::model()->findByPk($form->user_id);
                        $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('/recovery/recovery', array("activkey" => $user->activation, "email" => $user->email));
                        $subject = 'Password recovery information for ' . Yii::app()->name;
                        $message = 'Dear ' . $user->name . ',<br /><br />';
                        $message .= 'You have requested for password recovery for your ' . Yii::app()->name . ' account. To receive a new password, please go to ' . $activation_url;
                        $message .= '<br /><br />Warm regards,<br />' . Yii::app()->name . '<br />' . Yii::app()->params['adminEmail'] . '<br />http://' . $_SERVER["SERVER_NAME"];
                        Recovery::sendMailRecovery($user->email, $subject, $message);
                        Yii::app()->user->setFlash('success', 'Please check your email. The instructions was sent to your email address.');
                        $this->refresh();
                    } else {
                        Yii::app()->user->setFlash('error', 'Inputs are not matching with database info. Please re-type the info.');
                    }
                }
                $this->render('recovery', array('form' => $form));
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Recovery the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Recovery::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Recovery $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recovery-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
