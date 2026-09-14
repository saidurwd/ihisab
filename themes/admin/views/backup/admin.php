<?php
/* @var $this BackupController */
/* @var $model Backup */
$this->pageTitle = 'Database Backup - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Backups' => array('admin'),
    'Manage',
);
Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
");
?>
<div class="widget-box">
    <div class="widget-header">
        <h5>Database Backup</h5>
        <div class="widget-toolbar">
            <a data-action="settings" href="#"><i class="icon-cog"></i></a>
            <a data-action="reload" href="#"><i class="icon-refresh"></i></a>
            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
            <a data-action="close" href="#"><i class="icon-remove"></i></a>
        </div>
        <div class="widget-toolbar">
            <?php echo CHtml::link('<i class="fa fa-random"></i> EXPORT DATABASE', array('exportdatabase'), array('data-rel' => 'tooltip', 'title' => 'Export database', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
        </div>
    </div><!--/.widget-header -->
    <div class="widget-body">
        <div class="widget-main">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'backup-grid',
                    'dataProvider' => $model->search(),
                    'filter' => $model,
                    'afterAjaxUpdate' => 'reloadPageSetUp',
                    'htmlOptions' => array('class' => ''),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                    'template' => '{items}{pager}',
                    'emptyText' => 'No result found.',
                    'summaryText' => "{start} - {end} of {count} result",
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
                            'name' => 'created_on',
                            'type' => 'raw',
                            'value' => 'UserAdmin::get_date_time($data->created_on)',
                            'filter' => CHtml::activeTextField($model, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'name' => 'attachment',
                            'type' => 'raw',
                            'value' => '$data->attachment',
                            'filter' => CHtml::activeTextField($model, 'attachment', array('class' => 'form-control')),
                            'htmlOptions' => array('class' => 'text-left'),
                        ),
                        array(
                            'name' => 'created_by',
                            'type' => 'raw',
                            'value' => 'UserAdmin::get_user_name($data->created_by)',
                            'filter' => CHtml::activeDropDownList($model, 'created_by', CHtml::listData(UserAdmin::model()->findAll(array('condition' => '', 'order' => 'name')), 'id', 'name'), array('empty' => 'All', 'class' => 'select2')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                        'header' => 'Actions',
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                    ),
                        array(
                            'header' => 'Action',
                            'class' => 'CButtonColumn',
                            'htmlOptions' => array('class' => "text-center width-50"),
                            'template' => '{download} {delete}',
                            'buttons' => array(
                                'download' => array(
                                    'label' => '',
                                    'imageUrl' => '',
                                    'url' => 'yii::app()->createUrl("/backup/download", array("id"=>$data["id"]))',
                                    'options' => array('class' => 'btn btn-xs btn-warning fa fa-download', 'rel' => 'tooltip', 'data-original-title' => 'Download'),
                                ),
                                'delete' => array(
                                    'label' => '',
                                    'imageUrl' => '',
                                    'options' => array('class' => 'btn btn-xs btn-danger fa fa-times'),
                                ),
                            ),
                        ),
                    ),
                )
            );
            ?>
        </div>
    </div><!--/.widget-body -->
</div><!--/.widget-box -->