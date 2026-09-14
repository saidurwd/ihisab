<?php

/* @var $this TransactionController */
/* @var $model Transaction */
$this->pageTitle = 'Single Transaction - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transactions' => array('admin'),
    'Single Transaction',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<?php $this->renderPartial('_single', array('model' => $model)); ?>