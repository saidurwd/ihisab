<?php

class PrizebondDrawController extends BackEndController {

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
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'addNumber', 'edit', 'remove', 'import'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionImport() {
        $model = new ImportBondNumber;
        set_time_limit(0);

        if (isset($_POST['ImportBondNumber'])) {
            $model->attributes = $_POST['ImportBondNumber'];
            if ($model->validate()) {
                $path = Yii::app()->basePath . '/../uploads';
                if (isset($_POST['ImportBondNumber']['file_source'])) {
                    $model->file_source = $_POST['ImportBondNumber']['file_source'];
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
                    $objReader->setReadDataOnly(TRUE);
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
                        $model = new ImportBondNumber;
                        $model->draw_id = (int) trim($val[0]);
                        $model->winning_number = trim($val[1]);
                        $model->prize_id = (int) trim($val[2]);
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
        }

        $this->render('import', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model_result = new PrizebondDrawNumber('search');
        $model_result->unsetAttributes();  // clear any default values
        if (isset($_GET['PrizebondDrawNumber']))
            $model_result->attributes = $_GET['PrizebondDrawNumber'];

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'model_result' => $model_result,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PrizebondDraw;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PrizebondDraw'])) {
            $model->attributes = $_POST['PrizebondDraw'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAddNumber() {
        $model = new PrizebondDrawNumber;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PrizebondDrawNumber'])) {
            $model->attributes = $_POST['PrizebondDrawNumber'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('view', 'id' => $model->draw_id));
            }
        }

        $this->render('addNumber', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PrizebondDraw'])) {
            $model->attributes = $_POST['PrizebondDraw'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionEdit($id) {
        $model = $this->loadModelNumber($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PrizebondDrawNumber'])) {
            $model->attributes = $_POST['PrizebondDrawNumber'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('view', 'id' => $model->draw_id));
            }
        }

        $this->render('editNumber', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionRemove($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModelNumber($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('PrizebondDraw');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PrizebondDraw('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PrizebondDraw'])) {
            $model->attributes = $_GET['PrizebondDraw'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PrizebondDraw the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PrizebondDraw::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function loadModelNumber($id) {
        $model = PrizebondDrawNumber::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PrizebondDraw $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prizebond-draw-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
