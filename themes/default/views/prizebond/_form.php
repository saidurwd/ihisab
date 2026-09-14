<?php
/* @var $this PrizebondController */
/* @var $model Prizebond */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prizebond-form',
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
            <?php echo $form->labelEx($model, 'series'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'series', CHtml::listData(PrizebondSeries::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => '--please select--', 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'series', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'sl_number'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'sl_number', array('maxlength' => 10, 'class' => 'form-control', 'placeholder' => 'Number')); ?>
            <?php echo $form->error($model, 'sl_number', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'status'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'status', CHtml::listData(PrizebondStatus::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => '--please select--', 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'status', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'purchase_date'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'purchase_date', array('class' => 'form-control datepicker', 'value' => date('Y-m-d'), 'data-dateformat' => 'yy-mm-dd')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>