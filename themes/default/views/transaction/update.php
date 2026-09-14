<?php

/* @var $this TransactionController */
/* @var $model Transaction */
$this->pageTitle = 'Edit Transaction - ' . Yii::app()->name;
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<?php $this->renderPartial('_form', array('model' => $model)); ?>