<?php
/* @var $this TransactionController */
/* @var $model Transaction */
$this->pageTitle = Account::get_account($_REQUEST['id']) . ' - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transactions' => array('admin'),
    'Manage',
);

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $('#transaction-grid').yiiGridView('update', {
                data: $(this).serialize()
        });
        return false;
    });
    ", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#datepicker_created').datepicker();
        pageSetUp();
    }
    ", CClientScript::POS_END);
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts-3d.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
?>
<script>
    function reload_div()
    {
        alert('<?php echo Yii::app()->getRequest()->getUrl(); ?>');
        $("#transaction-grid").load('<?php echo Yii::app()->getRequest()->getUrl(); ?> #widget-grid');
    }
</script>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> 
            Account
            <span>> 
                <?php echo Account::get_account($_REQUEST['id']); ?>
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5> NET WORTH <?php echo Transaction::get_net_worth(); ?></h5>
            </li>
            <li class="sparks-info">
                <h5> BUDGET BALANCE <?php echo TransactionBudget::budget_balance_current_month(); ?></h5>
            </li>
            <li class="sparks-info">
                <h5> INCOME THIS MONTH <?php echo Transaction::get_income_current_month(); ?></h5>
            </li>
            <li class="sparks-info">
                <h5> EXPENSE THIS MONTH <?php echo Transaction::get_expense_current_month(); ?></h5>
            </li>
            <li class="sparks-info">
                <h5> SAVED THIS MONTH <?php echo Transaction::get_saved_current_month(); ?></h5>
            </li>
        </ul>
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
                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                    <h2>INCOME vs EXPENSE</h2>
                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">		
                            <a data-toggle="tab" href="#s1"><span class="hidden-mobile hidden-tablet">MONTH</span></a>
                        </li>
                    </ul>
                </header>
                <!-- widget div-->
                <div class="no-padding">
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        test
                    </div>
                    <!-- end widget edit box -->
                    <div class="widget-body">
                        <!-- content -->
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
                                <div id="comparisonChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-tasks"></i> </span>
                    <h2><?php echo Account::get_account($_REQUEST['id']); ?></h2>
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', array('transaction/create'), array('data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Add Transaction')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'transaction-grid',
                            'dataProvider' => $model->search_account($_REQUEST['id']),
                            'filter' => $model,
                            //'enableSorting' => false,
                            'afterAjaxUpdate' => 'reinstallDatePicker',
                            'htmlOptions' => array('class' => '',),
                            'itemsCssClass' => 'table table-striped table-bordered table-hover',
                            'template' => '{items}{pager}', //{summary}
                            'emptyText' => 'No Transaction Found.',
                            'summaryText' => "{start} - {end} of {count} Transactions",
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
                                    'name' => 'created',
                                    'type' => 'raw',
                                    'value' => 'UserAdmin::get_date($data->created)',
                                    'filter' => CHtml::activeTextField($model, 'created', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'Date'),
                                ),
                                array(
                                    'name' => 'amount',
                                    'type' => 'raw',
                                    'value' => 'Transaction::get_amount($data->transaction_type,$data->amount)',
                                    'filter' => CHtml::activeTextField($model, 'amount', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;", 'title' => 'Amount'),
                                ),
                                array(
                                    'name' => 'description',
                                    'type' => 'raw',
                                    'value' => '$data->description',
                                    'filter' => CHtml::activeTextField($model, 'description', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;width:250px;", 'title' => 'Description'),
                                ),
                                array(
                                    'name' => 'tag',
                                    'type' => 'raw',
                                    'value' => 'isset($gridTagMap[$data->id]) ? $gridTagMap[$data->id] : Tag::get_tags($data->id)',
                                    'filter' => $tagFilter,
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Tags'),
                                ),
                                array(
                                    'name' => 'account',
                                    'type' => 'raw',
                                    'value' => 'isset($gridAccountMap[$data->id]) ? $gridAccountMap[$data->id] : Account::get_accounts($data->id)',
                                    'filter' => CHtml::activeDropDownList($model, 'account', CHtml::listData($accountList, 'id', 'account_name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Account'),
                                ),
                                array(
                                    'header' => '',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:center;width:80px;", 'class' => ''),
                                    'template' => '{update} {delete}',
                                    'buttons' => array(
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-pencil', 'data-toggle' => 'modal', 'data-target' => '#editTransaction'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-trash-o'),
                                        ),
                                    ),
                                ),
                            ),
                        ));
                        ?>
                        <!-- widget footer -->
                        <div class="widget-footer">                            	
                            <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> Add Transaction', array('transaction/create'), array('class' => 'btn btn-labeled btn-success')); ?>
                        </div>
                        <!-- end widget footer -->
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
<div class="modal fade" id="editTransaction" role="dialog" aria-labelledby="editTransactionLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    $(function () {
        $('#comparisonChart').highcharts({
            title: {
                text: 'COMPARE INCOME vs EXPENSE',
                x: -20 //center
            },
            xAxis: {
                categories: [<?php echo Account::last_twelve_months(); ?>]
            },
            yAxis: {
                title: {
                    text: 'Amount'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: 'Income',
                    data: [<?php echo Transaction::accountIncomeChart($_REQUEST['id']); ?>]
                }, {
                    name: 'Expence',
                    data: [<?php echo Transaction::accountExpenseChart($_REQUEST['id']); ?>]
                }]
        });
    });
</script>