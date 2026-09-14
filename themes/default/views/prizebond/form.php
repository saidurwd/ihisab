<?php
/* @var $this PrizebondController */
/* @var $model Prizebond */
$this->pageTitle = 'Prize Bonds Claim form- ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prize Bonds' => array('admin'),
    'Claim form',
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-money fa-fw "></i> 
            Prize Bonds
            <span>>
                Claim form
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
                    <span class="widget-icon"> <i class="fa fa-file"></i> </span>
                    <h2>Prize Bonds - Claim form</h2>      
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'prizebond-grid',
                            'dataProvider' => $model->search_form(1),
                            //'filter' => $model,
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                            'template' => '{items}{pager}',
                            'pager' => array(
                                'htmlOptions' => array(
                                    'class' => 'pagination',
                                ),
                                'header' => '',
                                'selectedPageCssClass' => 'active',
                            ),
                            'pagerCssClass' => 'widget-footer',
                            'columns' => array(
                                array(
                                    'header' => 'Document',
                                    'name' => 'title',
                                    'type' => 'raw',
                                    'value' => 'CHtml::link($data->title, array("/document/download","id"=>$data->id),array("target"=>"_blank"))',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Document'),
                                ),
                                array(
                                    'name' => 'doc_size',
                                    'type' => 'raw',
                                    'value' => 'Document::byteToMbyte($data->doc_size)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Size'),
                                ),
                            ),
                        ));
                        ?>                        
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