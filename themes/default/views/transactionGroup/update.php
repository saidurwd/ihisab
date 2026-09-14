<?php
/* @var $this TransactionGroupController */
/* @var $model TransactionGroup */
$this->pageTitle = 'Edit Group - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transaction Groups' => array('admin'),
    $model->title => array('view', 'id' => $model->id),
    'Edit',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel">EDIT GROUP</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>