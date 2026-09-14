<?php
/* @var $this PrizebondDrawController */
/* @var $model PrizebondDraw */
/* @var $form TbActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'prizebond-draw-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>
    <p class="help-block">Fields with <span class="required">*</span> are required.</p>
    <?php echo $form->errorSummary($model); ?>
    <?php
    if ($model->isNewRecord) {
        echo $form->hiddenField($model, 'draw_id', array('value' => $_GET['id']));
    }
    ?>
    <?php echo $form->textFieldControlGroup($model, 'winning_number', array('span' => 5, 'maxlength' => 10)); ?>    
    <?php echo $form->dropDownListControlGroup($model, 'prize_id', CHtml::listData(PrizebondPrize::model()->findAll(array('condition' => '', "order" => "title")), 'id', 'title'), array('empty' => '--please select--', 'class' => 'span5')); ?>
    <div class="form-actions">
        <?php
        echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'size' => TbHtml::BUTTON_SIZE_LARGE,
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->