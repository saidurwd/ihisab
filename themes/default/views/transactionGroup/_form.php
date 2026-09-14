<?php
/* @var $this TransactionGroupController */
/* @var $model TransactionGroup */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'transaction-group-form',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array('class' => 'form-horizontal'),
        ));
?>
<fieldset>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'title'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Title')); ?>
            <?php echo $form->error($model, 'title', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'members'); ?>
        </label>
        <div class="col-md-8">
            <?php //echo $form->textField($model, 'members', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Members')); ?>
            <?php echo $form->dropDownList($model, 'members', CHtml::listData(TransactionContact::model()->findAll(array('condition' => 'user IN(0,' . Yii::app()->user->id . ')', 'order' => 'contact_name')), 'id', 'contact_name'), array('placeholder' => 'Name', 'multiple' => true, 'class' => 'select2')); ?>
            <?php echo $form->error($model, 'members', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->isNewRecord ? 'New' : $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>