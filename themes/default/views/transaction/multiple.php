<?php

/* @var $this TransactionController */
/* @var $model Transaction */
$this->pageTitle = 'Multiple Transactions - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transactions' => array('admin'),
    'Multiple Transactions',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<?php $this->renderPartial('_multiple', array('model' => $model)); ?>