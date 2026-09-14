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
    <?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5, 'maxlength' => 250)); ?>
    <div class="row-fluid">
        <div class="span5">
            <?php echo $form->labelEx($model, 'draw_date'); ?>
            <?php
            echo $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                'language' => 'en',
                'model' => $model, // Model object
                'attribute' => 'draw_date',
                'options' => array(
                    'mode' => 'date',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'yearRange' => '1900:2200',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => '',
                    'showTimepicker' => false,
                ),
                'htmlOptions' => array(
                    'placeholder' => 'Draw Date',
                    'class' => 'span12',
                ),
                    ), true);
            ?>
        </div>    
    </div>
    <div class="row-fluid">
        <div class="span5">
            <?php echo $form->labelEx($model, 'claim_last_date'); ?>
            <?php
            echo $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                'language' => 'en',
                'model' => $model, // Model object
                'attribute' => 'claim_last_date',
                'options' => array(
                    'mode' => 'date',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'yearRange' => '1900:2200',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => '',
                    'showTimepicker' => false,
                ),
                'htmlOptions' => array(
                    'placeholder' => 'Claim Last Date',
                    'class' => 'span12',
                ),
                    ), true);
            ?>
        </div>    
    </div>
    <?php echo $form->textAreaControlGroup($model, 'details', array('rows' => 6, 'span' => 8)); ?>
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