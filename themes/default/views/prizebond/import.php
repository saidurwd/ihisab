                  
<?php
$this->pageTitle = 'Import Prize Bonds - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prize Bonds' => array('admin'),
    'Import',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?> 
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-money fa-fw "></i> 
            Prize Bonds
            <span>>
                Import
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-upload"></i> </span>
                    <h2>Prize Bonds</h2>      
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', array('prizebond/create'), array('data-toggle' => 'modal', 'data-target' => '#newData', 'data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Add Prize Bond')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'import-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array('class' => 'smart-form', 'enctype' => 'multipart/form-data'),
                        ));
                        ?>  
                        <div class="alert alert-info fade in">
                            <i class="fa-fw fa fa-info"></i>
                            Please upload valid .xls or .xlsx file.
                        </div>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    <?php echo $form->labelEx($model, 'file_source'); ?>
                                </label>
                                <div class="col-md-8">
                                    <label for="file" class="input input-file" onchange="this.parentNode.nextSibling.value = this.value">
                                        <div class="button"> <?php echo $form->fileField($model, 'file_source', array('class' => '', 'onchange' => 'this.parentNode.nextSibling.value = this.value')); ?>Browse</div><input type="text" placeholder="Browse file" readonly="">
                                        <?php echo $form->error($model, 'file_source', array('class' => 'text-danger')); ?>
                                    </label>                
                                </div>
                            </div>
                        </fieldset>
                        <footer>   
                            <?php echo CHtml:: submitButton('Import', array('class' => 'btn btn-primary')); ?>
                        </footer>    
                        <?php $this->endWidget(); ?>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->