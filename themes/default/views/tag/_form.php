<?php
/* @var $this TagController */
/* @var $model Tag */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'tag-form',
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
            <?php echo $form->textField($model, 'tag_name', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Tag Name')); ?>
            <?php echo $form->error($model, 'tag_name', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'parent_tag'); ?>
        </label>
        <div class="col-md-8">
            <?php //echo $form->dropDownList($model, 'parent_tag', CHtml::listData(Tag::model()->findAll(array('condition' => '')), 'id', 'tag_name'), array('empty' => '--please select--', 'class' => 'form-control')); ?>
            <?php
            if ($model->isNewRecord) {
                echo Tag::get_tag_new('Tag', 'parent_tag');
            } else {
                echo Tag::get_tag_edit('Tag', 'parent_tag', $model->parent_tag);
            }
            ?>
            <?php echo $form->error($model, 'parent_tag', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>