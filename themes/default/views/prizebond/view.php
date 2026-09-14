<?php
/* @var $this AccountController */
/* @var $model Account */
$this->breadcrumbs = array(
    'Accounts' => array('admin'),
    'New Account',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel"><?php echo $model->title; ?></h4>
</div>
<div class="modal-body">
    <?php echo $model->introtext; ?>
</div>