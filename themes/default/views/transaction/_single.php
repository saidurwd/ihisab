<?php
/* @var $this TransactionController */
/* @var $model Transaction */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'transaction-form',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array('class' => 'form-horizontal'),
        ));
Yii::app()->clientScript->registerScript('show_hide', "  
        $(document).ready(function(){
            $('.AccountTo').hide();
            $('#Transaction_transaction_type').on('change', function() {
              if ( this.value == '3')
              {
                $('.AccountTo').show();
              } else {
                $('.AccountTo').hide();
              }
            });
    });
");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="addMultipleLabel">ADD SINGLE TRANSACTION</h4>
</div>
<div class="modal-body">
    <fieldset>
        <div class="form-group">
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'transaction_type'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'transaction_type', CHtml::listData(TransactionType::model()->findAll(array('condition' => 'status=1')), 'id', 'title'), array('class' => 'select2')); ?>
                <?php echo $form->error($model, 'transaction_type', array('class' => 'text-danger')); ?>
            </div>
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'created'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->textField($model, 'created', array('class' => 'form-control datepicker', 'value' => date('Y-m-d'), 'data-dateformat' => 'yy-mm-dd')); ?>                
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'description'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->textField($model, 'description', array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Description')); ?>
                <?php echo $form->error($model, 'description', array('class' => 'text-danger')); ?>
            </div>
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'tag'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'tag', CHtml::listData(Tag::model()->findAll(array('condition' => 'user IN(0,' . Yii::app()->user->id . ')', 'order' => 'tag_name')), 'id', 'tag_name', 'parent'), array('placeholder' => 'Tags', 'multiple' => true, 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'tag', array('class' => 'text-danger')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'amount'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->textField($model, 'amount', array('maxlength' => 13, 'class' => 'form-control', 'placeholder' => 'Amount')); ?>
                <?php echo $form->error($model, 'amount', array('class' => 'text-danger')); ?>
            </div>
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'group'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'group', CHtml::listData(TransactionGroup::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id)), 'id', 'title'), array('empty' => 'SELECT', 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'group', array('class' => 'text-danger')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'account'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'account', CHtml::listData(Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND status="Active"')), 'id', 'account_name'), array('options' => array(Account::get_default_account() => array('selected' => true)), 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'account', array('class' => 'text-danger')); ?>
            </div>
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'status'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'status', CHtml::listData(TransactionStatus::model()->findAll(array('condition' => 'status=1')), 'id', 'title'), array('class' => 'select2')); ?>
                <?php echo $form->error($model, 'status', array('class' => 'text-danger')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'AccountTo', array('class' => 'AccountTo')); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->dropDownList($model, 'AccountTo', CHtml::listData(Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND status="Active"')), 'id', 'account_name'), array('class' => 'select2 AccountTo')); ?>
                <?php echo $form->error($model, 'AccountTo', array('class' => 'text-danger AccountTo')); ?>
            </div>
            <label class="col-md-2 control-label">
                <?php echo $form->labelEx($model, 'notes'); ?>
            </label>
            <div class="col-md-4">
                <?php echo $form->textField($model, 'notes', array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Notes')); ?>
                <?php echo $form->error($model, 'notes', array('class' => 'text-danger')); ?>
            </div>
        </div>
    </fieldset>
</div>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>