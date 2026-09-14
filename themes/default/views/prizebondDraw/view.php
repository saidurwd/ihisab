<?php
/* @var $this PrizebondDrawController */
/* @var $model PrizebondDraw */
$this->pageTitle = $model->title . ' - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prize Bonds Draws' => array('admin'),
    $model->title,
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-desktop fa-fw "></i> 
            Prize Bonds Draw
            <span>>
                <?php echo $model->title; ?>
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
        <article class="col-sm-12">
            <!-- new widget -->
            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-folder-open"></i> </span>
                    <h2><?php echo $model->title; ?></h2>
                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">		
                            <a data-toggle="tab" href="#s1"><i class="fa fa-stack-exchange"></i> <span class="hidden-mobile hidden-tablet">RESULT</span></a>
                        </li>                       
                    </ul>
                </header>
                <!-- widget div-->
                <div class="no-padding">
                    <div class="widget-body">
                        <!-- content -->
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">                                
                                <?php
                                $this->widget('zii.widgets.grid.CGridView', array(
                                    'id' => 'prizebond-draw-grid',
                                    'dataProvider' => $model_result->search($model->id),
                                    //'filter' => $model_result,
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
                                            'name' => 'winning_number',
                                            'type' => 'raw',
                                            'value' => '$data->winning_number',
                                            'filter' => CHtml::activeTextField($model_result, 'winning_number', array('class' => 'form-control')),
                                            'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Number'),
                                        ),
                                        array(
                                            'name' => 'prize_id',
                                            'type' => 'raw',
                                            'value' => 'PrizebondPrize::getPrize($data->prize_id)',
                                            'filter' => CHtml::activeTextField($model_result, 'prize_id', array('class' => 'form-control')),
                                            'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Prize'),
                                        ),
                                        array(
                                            'header' => 'Prize Value',
                                            'type' => 'raw',
                                            'value' => 'PrizebondPrize::getPrizeValue($data->prize_id)',
                                            'filter' => false,
                                            'htmlOptions' => array('style' => "text-align:right;width:100px;", 'title' => 'Prize Value'),
                                        ),
                                        array(
                                            'header' => 'Status',
                                            'type' => 'raw',
                                            'value' => 'PrizebondDrawNumber::getMatch($data->winning_number)',
                                            'filter' => false,
                                            'htmlOptions' => array('style' => "text-align:center;width:100px;", 'title' => 'Match'),
                                        ),
                                    ),
                                ));
                                ?>
                            </div>
                            <!-- end s1 tab pane -->
                        </div>
                        <!-- end content -->
                    </div>
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
    </div>
    <!-- end row -->    
</section>
<!-- end widget grid -->