<?php

class UserController extends Controller {

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
                'actions' => array('index', 'view', 'register', 'activate'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'setting'),
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

    public function actionActivate($id, $activation) {
        $returnValue = Yii::app()->db->createCommand()
                ->select('activation')
                ->from('{{user}}')
                ->where('id=:id', array(':id' => $id))
                ->queryScalar();

        if (!empty($activation) OR ! empty($id)) {
            if ($activation == $returnValue) {
                Yii::app()->db->createCommand('UPDATE {{user}} SET `status` = 1 WHERE id=' . $id)->execute();
                Yii::app()->user->setFlash('success', 'Account verification successful. Please login.');
                $this->redirect(array('site/login'));
            } else {
                Yii::app()->user->setFlash('error', 'Account verification not successful. Please try again!');
                $this->redirect(array('site/login'));
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        User::checkUser($id);
        $model = $this->loadModel($id);
        $model_profile = $this->loadModelProfile($id);
        $model_setting = $this->loadModelSetting($id);
        $model_access = $this->loadModelAccess($id);
        //Update user
        $previuosFileName = $model_profile->profile_picture;
        $path = Yii::app()->basePath . '/../uploads/profile_picture';
        if (!is_dir($path)) {
            mkdir($path);
        }
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model_profile->attributes = $_POST['UserProfile'];
            //Picture upload script
            if (@!empty($_FILES['UserProfile']['name']['profile_picture'])) {
                $model_profile->profile_picture = $_POST['UserProfile']['profile_picture'];
                if ($model_profile->validate(array('profile_picture'))) {
                    $myFile = $path . '/' . $previuosFileName;
                    if ((is_file($myFile)) && (file_exists($myFile))) {
                        unlink($myFile);
                    }
                    $model_profile->profile_picture = CUploadedFile::getInstance($model_profile, 'profile_picture');
                } else {
                    $model_profile->profile_picture = '';
                }
                $model_profile->profile_picture->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model_profile->profile_picture)));
                $model_profile->profile_picture = time() . '_' . str_replace(' ', '_', strtolower($model_profile->profile_picture));
            } else {
                $model_profile->profile_picture = $previuosFileName;
            }

            if ($model->save()) {
                $model_profile->save();
                Yii::app()->user->setFlash('success', 'Profile was saved successfully.');
                $this->redirect(array('view', 'id' => $model->id));
            } else {
                print_r($model->getErrors());
                print_r($model_profile->getErrors());
            }
        }
        //Update settings
        if (isset($_POST['UserSetting'])) {
            $model_setting->attributes = $_POST['UserSetting'];
            if ($model_setting->save()) {
                Yii::app()->user->setFlash('success', 'Settings was saved successfully.');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        //Change password
        if (isset($_POST['ChangePassword'])) {
            $model_access->attributes = $_POST['ChangePassword'];
            if ($model_access->validate()) {
                $model_access->password = SHA1($model_access->password);
                $model_access->verifypassword = SHA1($model_access->verifypassword);
                $model_access->activation = md5(microtime());
                if ($model_access->save()) {
                    Yii::app()->user->setFlash('success', 'Password was saved successfully.');
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
        //First time password field showing null
        $model_access->password = null;

        $this->render('view', array(
            'model' => $model,
            'model_profile' => $model_profile,
            'model_setting' => $model_setting,
            'model_access' => $model_access,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionRegister() {
//        if (!isset($_GET['ajax']))
//            $this->redirect(array('site/index'));

        $this->layout = false;
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                $model->status = 2;
                $model->group_id = 7;
                $model->user_type = 0;
                $model->activation = md5(microtime());
                $model->registerDate = new CDbExpression('NOW()');
                $model->password = SHA1($model->password);
                $model->passwordConfirm = SHA1($model->passwordConfirm);
                if ($model->save()) {
                    $model_profile = new UserProfile;
                    $model_profile->user_id = $model->id;
                    $model_profile->save();
                    //Send mail to user
                    $message = "Hello " . $model->name . ", <br /><br />";
                    $message .= "Welcome to " . Yii::app()->name . ". Please click on the link below to activate your account.  Alternatively, you can copy and paste the complete URL on the address bar of your browser and then press the Enter key.  <br /><br />";
                    $message .= 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('user/activate', array("id" => $model->id, "activation" => $model->activation));
                    $message .= "<br /><br />Sincerely, <br />" . Yii::app()->name;
                    $to = $model->email;
                    $subject = 'Welcome to ' . Yii::app()->name;
                    $fromName = Yii::app()->params['adminName'];
                    $fromMail = Yii::app()->params['adminEmail'];
                    User::sendMail($to, $subject, $message, $fromName, $fromMail);

                    //Send mail to site administrator
                    $to_bcc = Yii::app()->params['adminEmail'];
                    $subject_bcc = 'Account Details for ' . $model->name . ' at ' . Yii::app()->name;
                    $fromName_bcc = Yii::app()->params['adminName'];
                    $fromMail_bcc = Yii::app()->params['adminEmail'];
                    $bccList_bcc = UserAdmin::adminuser_email_list();
                    $message_bcc = 'Hello Administrator,<br /><br />';
                    $message_bcc .= 'A new user has registered at ' . Yii::app()->name . '.<br />';
                    $message_bcc .= 'This e-mail contains their details:<br /><br />';
                    $message_bcc .= 'Name: ' . $model->name . '.<br />';
                    $message_bcc .= 'E-mail: ' . $model->email . '.<br />';
                    $message_bcc .= 'Username: ' . $model->username . '.<br /><br />';
                    $message_bcc .= 'Please do not respond to this message. It is automatically generated and is for information purposes only.';
                    User::sendMailBCC($to_bcc, $subject_bcc, $message_bcc, $fromName_bcc, $fromMail_bcc, $bccList_bcc);
                    //Success message
                    Yii::app()->user->setFlash('success', 'Thanks for registering with us! Please check your email to activate account.');
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
            echo $this->renderPartial('create', array('model' => $model,), true, true);
            Yii::app()->end();
        } else {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                $model->status = 2;
                $model->group_id = 7;
                $model->user_type = 0;
                $model->activation = md5(microtime());
                $model->registerDate = new CDbExpression('NOW()');
                $model->password = SHA1($model->password);
                $model->passwordConfirm = SHA1($model->passwordConfirm);
                if ($model->save()) {
                    $model_profile = new UserProfile;
                    $model_profile->user_id = $model->id;
                    $model_profile->save();
                    //Send mail to user
                    $message = "Hello " . $model->name . ", <br /><br />";
                    $message .= "Welcome to " . Yii::app()->name . ". Please click on the link below to activate your account.  Alternatively, you can copy and paste the complete URL on the address bar of your browser and then press the Enter key.  <br /><br />";
                    $message .= 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('user/activate', array("id" => $model->id, "activation" => $model->activation));
                    $message .= "<br /><br />Sincerely, <br />" . Yii::app()->name;
                    $to = $model->email;
                    $subject = 'Welcome to ' . Yii::app()->name;
                    $fromName = Yii::app()->params['adminName'];
                    $fromMail = Yii::app()->params['adminEmail'];
                    User::sendMail($to, $subject, $message, $fromName, $fromMail);

                    //Send mail to site administrator
                    $to_bcc = Yii::app()->params['adminEmail'];
                    $subject_bcc = 'Account Details for ' . $model->name . ' at ' . Yii::app()->name;
                    $fromName_bcc = Yii::app()->params['adminName'];
                    $fromMail_bcc = Yii::app()->params['adminEmail'];
                    $bccList_bcc = UserAdmin::adminuser_email_list();
                    $message_bcc = 'Hello Administrator,<br /><br />';
                    $message_bcc .= 'A new user has registered at ' . Yii::app()->name . '.<br />';
                    $message_bcc .= 'This e-mail contains their details:<br /><br />';
                    $message_bcc .= 'Name: ' . $model->name . '.<br />';
                    $message_bcc .= 'E-mail: ' . $model->email . '.<br />';
                    $message_bcc .= 'Username: ' . $model->username . '.<br /><br />';
                    $message_bcc .= 'Please do not respond to this message. It is automatically generated and is for information purposes only.';
                    User::sendMailBCC($to_bcc, $subject_bcc, $message_bcc, $fromName_bcc, $fromMail_bcc, $bccList_bcc);
                    //Success message
                    Yii::app()->user->setFlash('success', 'Thanks for registering with us! Please check your email to activate account.');
                    Yii::app()->end();
                }
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
        User::checkUser($id);
        $this->layout = false;
        $model = $this->loadModel($id);
        $model_profile = $this->loadModelProfile($id);

        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                $model_profile->attributes = $_POST['UserProfile'];
                if ($model->save()) {
                    $model_profile->save();
                    Yii::app()->user->setFlash('success', 'Profile was saved successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('update', array('model' => $model, 'model_profile' => $model_profile,), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                $model_profile->save();
                Yii::app()->user->setFlash('success', 'Profile was saved successfully.');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'model_profile' => $model_profile,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelProfile($id) {
        $model = UserProfile::model()->findByAttributes(array('user_id' => $id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelSetting($id) {
        if (($model = UserSetting::model()->find(array('condition' => 'user=' . $id))) === null) {
            $model = new UserSetting;
            $model->user = $id;
            $model->save();
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelAccess($id) {
        $model = ChangePassword::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
