<?php

class PrizebondController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('index', 'view', 'admin', 'delete', 'create', 'update', 'import', 'match', 'form', 'faq'),
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

    public function actionForm() {
        $model = new Document('search_form');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Document']))
            $model->attributes = $_GET['Document'];

        $this->render('form', array(
            'model' => $model,
        ));
    }

    public function actionFaq() {
        $model = new Content('search_faq');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Content']))
            $model->attributes = $_GET['Content'];

        $this->render('faq', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModelContent($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
//        if (!isset($_GET['ajax']))
//            $this->redirect(array('prizebond/admin'));
        
        $this->layout = false;
        $model = new Prizebond;

        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Prizebond'])) {
                $model->attributes = $_POST['Prizebond'];
                $model->user = Yii::app()->user->id;
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Prizebond has been created successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('create', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Prizebond'])) {
            $model->attributes = $_POST['Prizebond'];
            $model->user = Yii::app()->user->id;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Prizebond has been created successfully.');
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionImport() {
        $model = new Prizebond;
        set_time_limit(0);

        if (isset($_POST['Prizebond'])) {
            $model->attributes = $_POST['Prizebond'];
            $path = Yii::app()->basePath . '/../uploads';
            if (isset($_POST['Prizebond']['file_source'])) {
                $model->file_source = $_POST['Prizebond']['file_source'];
                $model->file_source = CUploadedFile::getInstance($model, 'file_source');
                $ufilename = $model->file_source;
                if ($ufilename != "") {
                    $model->file_source->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->file_source)));
                    $model->file_source = time() . '_' . str_replace(' ', '_', strtolower($model->file_source));
                }
                $file = $path . "/" . $model->file_source;
                //save data to table
                Yii::import('ext.phpexcel.XPHPExcel');
                $objPHPExcel = XPHPExcel::createPHPExcel();

                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($file);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                }
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $val = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val[] = $cell->getValue();
                    }
                    $model = new Prizebond;
                    $model->user = Yii::app()->user->id;
                    $model->series = null;
                    $model->sl_number = trim($val[0]);
                    $model->purchase_date = new CDbExpression('NOW()');
                    $model->status = 1;
                    print trim($val[0]);
                    exit;
                    if (!$model->save()) {
                        print_r($model->getErrors());
                    }
                }
                //Remove uploaded file
                if ((is_file($file)) && (file_exists($file))) {
                    unlink($file);
                }
            }
            Yii::app()->user->setFlash('success', 'Prize Bond Number was imported successfully.');
            $this->redirect(array('admin'));
        }

        $this->render('import', array(
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
//            $this->redirect(array('prizebond/admin'));
        
        Prizebond::checkUser($id);
        $this->layout = false;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Prizebond'])) {
                $model->attributes = $_POST['Prizebond'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Prizebond has been updated successfully.');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('update', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['Prizebond'])) {
            $model->attributes = $_POST['Prizebond'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Prizebond has been updated successfully.');
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
        $dataProvider = new CActiveDataProvider('Prizebond');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Prizebond('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Prizebond']))
            $model->attributes = $_GET['Prizebond'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a matches numbers.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionMatch() {
        $model = new Prizebond('search_match');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Prizebond']))
            $model->attributes = $_GET['Prizebond'];

        $this->render('match', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Prizebond the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Prizebond::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelContent($id) {
        $model = Content::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Prizebond $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prizebond-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
