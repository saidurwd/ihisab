<?php
/* @var $this AccountController */
/* @var $model Account */
$this->pageTitle = 'Accounts - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Accounts' => array('admin'),
    'Manage',
);
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts-3d.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-list-alt fa-fw "></i> 
            Accounts
            <span>>
                Manage
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
                    <h2>Last Twelve Months</h2>
                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">		
                            <a data-toggle="tab" href="#s1"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">BALANCE</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s2"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">COMPARISON</span></a>
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
                                <div id="balanceChart" style="height: 400px"></div>
                            </div>
                            <!-- end s1 tab pane -->
                            <div class="tab-pane fade" id="s2">
                                <div id="comparisonChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                            </div>
                            <!-- end s2 tab pane -->
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
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>Accounts</h2>      
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', array('create'), array('data-toggle' => 'modal', 'data-target' => '#newData', 'data-placement' => 'bottom', 'rel' => 'tooltip', 'data-original-title' => 'Add Account')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'account-grid',
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            //'enableSorting' => false,
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'datatables table table-bordered table-striped table-hover smart-form',
                            'template' => '{items}{pager}',
                            'emptyText' => 'No Account Found.',
                            'summaryText' => "{start} - {end} of {count} Accounts",
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
                                    'name' => 'account_name',
                                    'type' => 'raw',
                                    'value' => 'CHtml::link($data->account_name, array("transaction/account","id"=>$data->id))',
                                    'filter' => CHtml::activeTextField($model, 'account_name', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Name'),
                                ),
                                array(
                                    'name' => 'institution',
                                    'type' => 'raw',
                                    'value' => '$data->institution',
                                    'filter' => CHtml::activeTextField($model, 'institution', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Financial Institution'),
                                ),
                                array(
                                    'header' => 'Available',
                                    'type' => 'raw',
                                    'value' => 'Account::get_balance($data->id)',
                                    'htmlOptions' => array('style' => "text-align:right;white-space:nowrap;", 'title' => 'Balance'),
                                ),
                                array(
                                    'name' => 'credit_limit',
                                    'type' => 'raw',
                                    'value' => 'number_format($data->credit_limit, 2, ".", ",")',
                                    'filter' => CHtml::activeTextField($model, 'credit_limit', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:right;white-space:nowrap;", 'title' => 'Credit Limit'),
                                ),
                                array(
                                    'name' => 'account_type',
                                    'type' => 'raw',
                                    'filter' => CHtml::activeDropDownList($model, 'account_type', CHtml::listData($accountTypes, 'id', 'title'), array('empty' => 'All', 'class' => 'form-control')),
                                    'value' => 'AccountType::get_type($data->account_type)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Type'),
                                ),
                                array(
                                    'name' => 'currency',
                                    'type' => 'raw',
                                    'filter' => CHtml::activeDropDownList($model, 'currency', CHtml::listData($currencies, 'id', 'currency_name'), array('empty' => 'All', 'class' => 'form-control')),
                                    'value' => 'Currency::get_currency($data->currency)',
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Currency'),
                                ),
                                array(
                                    'name' => 'default_account',
                                    'value' => '$data->default_account?Yii::t(\'app\',\'Yes\'):Yii::t(\'app\', \'No\')',
                                    'filter' => CHtml::activeDropDownList($model, 'default_account', array('0' => 'No', '1' => 'Yes'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:center;"),
                                ),
                                array(
                                    'name' => 'status',
                                    'value' => '$data->status',
                                    'filter' => CHtml::activeDropDownList($model, 'status', array('Active' => 'Active', 'Inactive' => 'Inactive'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:center;"),
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
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-pencil', 'data-toggle' => 'modal', 'data-target' => '#newData'),
                                        ),
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'data-toggle' => 'modal', 'data-target' => '#newData'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o'),
                                        ),
                                    ),
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
<script type="text/javascript">
    $(function () {
        $('#balanceChart').highcharts({
            chart: {
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 25,
                    depth: 70
                }
            },
            title: {
                text: 'Monthly Balances'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: [<?php echo Account::last_twelve_months(); ?>]
            },
            yAxis: {
                opposite: true
            },
            series: [{
                    name: 'Balances',
                    data: [<?php echo $balanceChartData; ?>]
                }]
        });
        $('#comparisonChart').highcharts({
            title: {
                text: 'COMPARE INCOME vs EXPENSE vs NET WORTH',
                x: -20 //center
            },
            credits: {
                enabled: false
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
                    data: [<?php echo $incomeComparisonData; ?>]
                }, {
                    name: 'Expence',
                    data: [<?php echo $expenseComparisonData; ?>]
                }, {
                    name: 'Balances',
                    data: [<?php echo $balanceComparisonData; ?>]
                }, {
                    name: 'Net Worth',
                    data: [<?php echo $worthComparisonData; ?>]
                }]
        });
    });
</script>