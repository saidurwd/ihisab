<?php
/* @var $this TagController */
/* @var $model Tag */
$this->pageTitle = 'Edit Tag - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Tags' => array('admin'),
    $model->tag_name => array('view', 'id' => $model->id),
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
    <h4 class="modal-title" id="newDataLabel">EDIT TAG</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>