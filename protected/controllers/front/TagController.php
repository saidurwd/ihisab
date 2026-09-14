<?php

class TagController extends Controller {

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
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'ordering'),
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

    public function actionOrdering() {
        set_time_limit(0);
        //for path
        $parent = Tag::model()->findAll(array('condition' => 'parent_tag=0 OR parent_tag IS NULL'));
        foreach ($parent as $key => $values) {
            Yii::app()->db->createCommand('UPDATE {{tag}} SET `path` = CONCAT("0.",' . $values["id"] . ') WHERE id=' . (int) $values["id"])->execute();
        }
        $parent1 = Tag::model()->findAll(array('condition' => 'parent_tag=0 OR parent_tag IS NULL'));
        foreach ($parent1 as $key => $values1) {
            $parent2 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values1["id"]));
            foreach ($parent2 as $key => $values2) {
                Yii::app()->db->createCommand('UPDATE {{tag}} SET `path` = CONCAT("' . $values1["path"] . '",".",' . $values2["id"] . ') WHERE id=' . (int) $values2["id"])->execute();

                $parent3 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values2["id"]));
                foreach ($parent3 as $key => $values3) {
                    Yii::app()->db->createCommand('UPDATE {{tag}} SET `path` = CONCAT("' . $values2["path"] . '",".",' . $values3["id"] . ') WHERE id=' . (int) $values3["id"])->execute();

                    $parent4 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values3["id"]));
                    foreach ($parent4 as $key => $values4) {
                        Yii::app()->db->createCommand('UPDATE {{tag}} SET `path` = CONCAT("' . $values3["path"] . '",".",' . $values4["id"] . ') WHERE id=' . (int) $values4["id"])->execute();
                    }
                }
            }
        }

        //for alias
        $parent = Tag::model()->findAll(array('condition' => 'parent_tag=0 OR parent_tag IS NULL'));
        foreach ($parent as $key => $values) {
            Yii::app()->db->createCommand('UPDATE {{tag}} SET `alias` = "' . $values["tag_name"] . '" WHERE id=' . (int) $values["id"])->execute();
        }
        $parent1 = Tag::model()->findAll(array('condition' => 'parent_tag=0 OR parent_tag IS NULL'));
        foreach ($parent1 as $key => $values1) {
            $parent2 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values1["id"]));
            foreach ($parent2 as $key => $values2) {
                Yii::app()->db->createCommand('UPDATE {{tag}} SET `alias` = CONCAT("' . $values1["alias"] . '","/","' . $values2["tag_name"] . '") WHERE id=' . (int) $values2["id"])->execute();

                $parent3 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values2["id"]));
                foreach ($parent3 as $key => $values3) {
                    Yii::app()->db->createCommand('UPDATE {{tag}} SET `alias` = CONCAT("' . $values2["alias"] . '","/","' . $values3["tag_name"] . '") WHERE id=' . (int) $values3["id"])->execute();

                    $parent4 = Tag::model()->findAll(array('condition' => 'parent_tag = ' . (int) $values3["id"]));
                    foreach ($parent4 as $key => $values4) {
                        Yii::app()->db->createCommand('UPDATE {{tag}} SET `alias` = CONCAT("' . $values3["alias"] . '","/","' . $values4["tag_name"] . '") WHERE id=' . (int) $values4["id"])->execute();
                    }
                }
            }
        }

        Yii::app()->user->setFlash('success', "Tag ordering was updated successfully.");
        $this->redirect(array('admin'));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        Tag::checkUser($id);
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
//            $this->redirect(array('tag/admin'));

        $this->layout = false;
        $model = new Tag;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Tag'])) {
                $model->attributes = $_POST['Tag'];
                $model->created = new CDbExpression('NOW()');
                $model->user = Yii::app()->user->id;
                if ($model->save()) {
                    //add path & alias
                    Tag::update_path($model->id);
                    Tag::update_alias($model->id);
                    Yii::app()->user->setFlash('success', 'Tag has been created successfully');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('create', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Tag'])) {
            $model->attributes = $_POST['Tag'];
            $model->created = new CDbExpression('NOW()');
            $model->user = Yii::app()->user->id;
            if ($model->save()) {
                //add path & alias
                Tag::update_path($model->id);
                Tag::update_alias($model->id);
                Yii::app()->user->setFlash('success', 'Tag has been created successfully');
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
//            $this->redirect(array('tag/admin'));

        $this->layout = false;
        Tag::checkUser($id);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Tag'])) {
                $model->attributes = $_POST['Tag'];
                $model->modified = new CDbExpression('NOW()');
                if ($model->save()) {
                    //add path & alias
                    Tag::update_path($model->id);
                    Tag::update_alias($model->id);
                    Yii::app()->user->setFlash('success', 'Tag has been updated successfully');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('update', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Tag'])) {
            $model->attributes = $_POST['Tag'];
            $model->modified = new CDbExpression('NOW()');
            if ($model->save()) {
                //add path & alias
                Tag::update_path($model->id);
                Tag::update_alias($model->id);
                Yii::app()->user->setFlash('success', 'Tag has been updated successfully');
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
        Tag::checkUser($id);
        $user = Tag::get_user($id);
        if ($user == Yii::app()->user->id) {
            $this->loadModel($id)->delete();
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Tag');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Tag('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Tag']))
            $model->attributes = $_GET['Tag'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Tag the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Tag::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Tag $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tag-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
