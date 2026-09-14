<?php
/* @var $this PrizebondController */
/* @var $model Prizebond */
$this->pageTitle = 'Prize Bonds Match- ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prize Bonds' => array('admin'),
    'Match',
);
Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#datepicker_created').datepicker();
        pageSetUp();
    }
    ", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-money fa-fw "></i> 
            Prize Bonds
            <span>>
                Match
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
                    <h2>Prize Bonds Match</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'prizebond-grid',
                            'dataProvider' => $model->search_match(),
                            //'filter' => $model,
                            'afterAjaxUpdate' => 'reinstallDatePicker',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                            'template' => '{items}{pager}',
                            'emptyText' => 'Sorry No Match found, Purchase more to try your luck.',
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
                                    'header' => 'Number',
                                    'name' => 'sl_number',
                                    'type' => 'raw',
                                    'value' => '$data->sl_number',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => ''),
                                ),
                                array(
                                    'header' => 'Purchase Date',
                                    'name' => 'purchase_date',
                                    'type' => 'raw',
                                    'value' => 'UserAdmin::get_date($data->purchase_date)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => ''),
                                ),
                                array(
                                    'header' => 'Draw No.',
                                    'name' => 'draw_id',
                                    'type' => 'raw',
                                    'value' => 'PrizebondDraw::get_title($data->draw_id)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => ''),
                                ),
                                array(
                                    'header' => 'Match Status',
                                    'name' => 'prize_id',
                                    'type' => 'raw',
                                    'value' => 'PrizebondPrize::getPrize($data->prize_id)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => ''),
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