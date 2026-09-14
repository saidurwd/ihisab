<?php
/* @var $this TransactionBudgetController */
/* @var $model TransactionBudget */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'transaction-budget-form',
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
            <?php echo $form->labelEx($model, 'tag_name'); ?>
        </label>
        <div class="col-md-8">
            <?php
            if ($model->isNewRecord) {
                echo Tag::get_tag_new("TransactionBudget", "tag_name");
            } else {
                echo Tag::get_tag_edit("TransactionBudget", "tag_name", $model->tag_name);
            }
            ?>
            <?php echo $form->error($model, 'tag_name', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'amount'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'amount', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Amount')); ?>
            <?php echo $form->error($model, 'amount', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>