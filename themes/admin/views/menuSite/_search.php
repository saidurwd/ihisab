<?php
/* @var $this MenuSiteController */
/* @var $model MenuSite */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <?php echo $form->textField($model, 'id', array('span' => 5, 'maxlength' => 10)); ?>

    <?php echo $form->textField($model, 'parent', array('span' => 5)); ?>

    <?php echo $form->textField($model, 'title', array('span' => 5, 'maxlength' => 150)); ?>

    <?php echo $form->textField($model, 'controller', array('span' => 5, 'maxlength' => 50)); ?>

    <?php echo $form->textField($model, 'url', array('span' => 5, 'maxlength' => 100)); ?>

    <?php echo $form->textField($model, 'icon', array('span' => 5, 'maxlength' => 50)); ?>

    <?php echo $form->textField($model, 'ordering', array('span' => 5)); ?>

        <?php echo $form->textField($model, 'status', array('span' => 5)); ?>

    <div class="form-actions">
    <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->