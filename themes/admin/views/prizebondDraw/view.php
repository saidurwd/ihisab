<?php
/* @var $this PrizebondDrawController */
/* @var $model PrizebondDraw */
?>

<?php
$this->pageTitle = 'Draw details - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Prizebond Draws' => array('admin'),
    $model->title,
);
?>
<div class="widget-box">
    <div class="widget-header">
        <h5>Details Draw (<?php echo $model->title; ?>)</h5>
        <div class="widget-toolbar">
            <a data-action="settings" href="#"><i class="icon-cog"></i></a>
            <a data-action="reload" href="#"><i class="icon-refresh"></i></a>
            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
            <a data-action="close" href="#"><i class="icon-remove"></i></a>
        </div>
        <div class="widget-toolbar">
            <?php echo CHtml::link('<i class="icon-pencil"></i>', array('update', 'id' => $model->id), array('data-rel' => 'tooltip', 'title' => 'Edit', 'data-placement' => 'bottom')); ?>
        </div>
        <div class="widget-toolbar">
            <?php echo CHtml::link('<i class="icon-plus"></i>', array('create'), array('data-rel' => 'tooltip', 'title' => 'Add', 'data-placement' => 'bottom')); ?>
        </div>
    </div><!--/.widget-header -->
    <div class="widget-body">
        <div class="widget-main">
            <div class="widget-box">
                <div class="user-profile row-fluid">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-18">
                            <li class="active">
                                <a data-toggle="tab" href="#tab1">
                                    <i class="green icon-home bigger-120"></i>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab2">
                                    <i class="orange icon-table bigger-120"></i>
                                    Numbers
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content no-border padding-24">
                            <div id="tab1" class="tab-pane in active">
                                <?php
                                $this->widget('zii.widgets.CDetailView', array(
                                    'htmlOptions' => array(
                                        'class' => 'table table-striped table-condensed table-hover',
                                    ),
                                    'data' => $model,
                                    'attributes' => array(
                                        'id',
                                        'title',
                                        array(
                                            'name' => 'draw_date',
                                            'type' => 'raw',
                                            'value' => UserAdmin::get_date_time($model->draw_date),
                                        ),
                                        array(
                                            'name' => 'claim_last_date',
                                            'type' => 'raw',
                                            'value' => UserAdmin::get_date_time($model->claim_last_date),
                                        ),
                                        array(
                                            'name' => 'details',
                                            'type' => 'raw',
                                            'value' => $model->details,
                                            'htmlOptions' => array('style' => "text-align:left;"),
                                        ),
                                    ),
                                ));
                                ?>
                            </div>
                            <div id="tab2" class="tab-pane">
                                <?php echo CHtml::link('ADD NUMBER', array('addNumber', 'id' => $model->id), array('class' => 'btn btn-primary btn-small')); ?>
                                <?php echo CHtml::link('IMPORT NUMBER', array('import', 'id' => $model->id), array('class' => 'btn btn-primary btn-small')); ?>
                                <?php
                                $this->widget('bootstrap.widgets.TbGridView', array(
                                    'id' => 'prizebond-number-grid',
                                    'dataProvider' => $model_result->search($model->id),
                                    'filter' => $model_result,
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
                                            'class' => 'bootstrap.widgets.TbButtonColumn',
                                            'header' => 'Actions',
                                            'template' => '{update} {delete}',
                                            'buttons' => array
                                                (
                                                'update' => array
                                                    (
                                                    'label' => 'Edit',
                                                    'url' => 'Yii::app()->createUrl("/prizebondDraw/edit", array("id"=>$data["id"]))',
                                                    'options' => array('class' => 'update'),
                                                ),
                                                'delete' => array
                                                    (
                                                    'label' => 'Delete',
                                                    'url' => 'Yii::app()->createUrl("/prizebondDraw/remove", array("id"=>$data["id"]))',
                                                    'options' => array('class' => 'delete'),
                                                ),
                                            ),
                                        ),
                                    ),
                                ));
                                ?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.widget-body -->
</div><!--/.widget-box -->