<?php
/* @var $this PrizebondDrawController */
/* @var $model PrizebondDraw */
$this->pageTitle = 'Prize Bonds Draws - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prizebond Draws' => array('admin'),
    'Manage',
);
Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#datepicker_draw_date').datepicker();
        $('#datepicker_created').datepicker();
        pageSetUp();
    }
    ", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-desktop fa-fw "></i> 
            Prize Bonds Draws
            <span>>
                Manage
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
                    <span class="widget-icon"> <i class="fa fa-folder"></i> </span>
                    <h2>Prize Bonds Draws</h2> 
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'prizebond-draw-grid',
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'afterAjaxUpdate' => 'reinstallDatePicker',
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
                                    'name' => 'title',
                                    'type' => 'raw',
                                    'value' => 'CHtml::link(CHtml::encode($data->title), array("view","id"=>$data->id))',
                                    'filter' => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Title'),
                                ),
                                array(
                                    'name' => 'draw_date',
                                    'type' => 'raw',
                                    'value' => 'UserAdmin::get_date($data->draw_date)',
                                    'filter' => CHtml::activeTextField($model, 'draw_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'Draw Date'),
                                ),
                                array(
                                    'name' => 'claim_last_date',
                                    'type' => 'raw',
                                    'value' => 'UserAdmin::get_date($data->claim_last_date)',
                                    'filter' => CHtml::activeTextField($model, 'claim_last_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'Claim Last Date'),
                                ),
                                array(
                                    'name' => 'details',
                                    'type' => 'raw',
                                    'value' => '$data->details',
                                    'filter' => CHtml::activeTextField($model, 'details', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Details'),
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