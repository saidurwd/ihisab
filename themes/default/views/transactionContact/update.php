<?php
/* @var $this TransactionContactController */
/* @var $model TransactionContact */
$this->pageTitle = 'Edit Contact - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Contacts' => array('admin'),
    $model->contact_name => array('view', 'id' => $model->id),
    'Update',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel">EDIT CONTACT</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>