<?php
/* @var $this TransactionContactController */
/* @var $model TransactionContact */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'transaction-contact-form',
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
            <?php echo $form->labelEx($model, 'contact_name'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'contact_name', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Name')); ?>
            <?php echo $form->error($model, 'contact_name', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'email'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'email', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
            <?php echo $form->error($model, 'email', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>