<?php
$this->pageTitle = 'Import Numbers - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prizebond Draws' => array('admin'),
    'Create',
);
Yii::app()->clientScript->registerScript('banner', "
    $('#ImportBondNumber_file_source').ace_file_input({
        no_file: 'No file ...',
        btn_choose: 'Choose',
        btn_change: 'Change',
        droppable: false,
        onchange: null,
        thumbnail: false, //| true | large
        //whitelist:'gif|png|jpg|jpeg'
        //blacklist:'exe|php'
        //onchange:''
        //
    });
");
?>
<div class="widget-box">
    <div class="widget-header">
        <h5>Import Numbers</h5>
        <div class="widget-toolbar">
            <a data-action="settings" href="#"><i class="icon-cog"></i></a>
            <a data-action="reload" href="#"><i class="icon-refresh"></i></a>
            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
            <a data-action="close" href="#"><i class="icon-remove"></i></a>
        </div>
    </div><!--/.widget-header -->
    <div class="widget-body">
        <div class="widget-main">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'banner-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
            ?>
            <?php echo $form->errorSummary($model); ?>  
            <?php echo $form->hiddenField($model, 'draw_id', array('value' => $_GET['id'])); ?>
            <div class="row-fluid">
                <div class="span5">
                    <?php echo $form->fileFieldControlGroup($model, 'file_source', array('maxlength' => 255, 'class' => 'span12')); ?>
                </div>
            </div>            
            <div class="form-actions">
                <?php echo TbHtml::submitButton('Import', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
                <?php echo TbHtml::resetButton('Reset', array('color' => TbHtml::BUTTON_COLOR_INFO)); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div><!--/.widget-body -->
</div><!--/.widget-box -->