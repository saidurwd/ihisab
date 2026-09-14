<?php
/* @var $this PrizebondController */
/* @var $model Prizebond */

$this->pageTitle = 'Edit Prizebond - ' . Yii::app()->name;
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel">EDIT PRIZE BOND</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>