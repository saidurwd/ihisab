<?php
/* @var $this SiteController */
$this->pageTitle = 'Dashboard - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Dashboard',
);
//$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
//$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts-3d.js', CClientScript::POS_END);
//$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-dashboard fa-fw "></i>
                Dashboard - Year <?= date('Y') ?>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <ul id="sparks" class="">
                <li class="sparks-info">
                    <h5> NET WORTH <?= Transaction::get_net_worth() ?></h5>
                </li>
                <li class="sparks-info">
                    <h5> BUDGET BALANCE <?= TransactionBudget::budget_balance_current_month() ?></h5>
                </li>
                <li class="sparks-info">
                    <h5> INCOME THIS MONTH <?= Transaction::get_income_current_month() ?></h5>
                </li>
                <li class="sparks-info">
                    <h5> EXPENSE THIS MONTH <?= Transaction::get_expense_current_month() ?></h5>
                </li>
                <li class="sparks-info">
                    <h5> SAVED THIS MONTH <?= Transaction::get_saved_current_month() ?></h5>
                </li>
            </ul>
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
                        <span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
                        <h2 style="text-transform: uppercase;">Current Month By TAG</h2>
                    </header>
                    <!-- widget div-->
                    <div>
                        <!-- widget content -->
                        <div class="widget-body no-padding">
                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'id' => 'tag-grid',
                                'dataProvider' => $model_dashboard_report->dashboard_report(),
//                                'filter' => $model_dashboard_report,
                                //'enableSorting' => false,
                                'afterAjaxUpdate' => 'reloadPageSetUp',
                                'htmlOptions' => array('class' => ''),
                                'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                                'template' => '{items}{pager}',
                                'emptyText' => 'No Tag Found.',
                                'summaryText' => "{start} - {end} of {count} Tags",
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
                                        'name' => 'tag_name',
                                        'type' => 'raw',
                                        'value' => 'CHtml::link($data->tag_name, array("transaction/tag","id"=>$data->id))',
                                        'htmlOptions' => array('style' => "text-align:left;"),
                                    ),
                                    array(
                                        'header' => 'Expanse',
                                        'type' => 'raw',
                                        'value' => 'Transaction::get_amount(1, Transaction::expense_specific_month_tag(date("Y-m-t"), $data->id))',
                                        'htmlOptions' => array('style' => "text-align:left;"),
                                    ),
                                    array(
                                        'header' => 'Income',
                                        'type' => 'raw',
                                        'value' => 'Transaction::get_amount(2, Transaction::income_specific_month_tag(date("Y-m-t"), $data->id))',
                                        'htmlOptions' => array('style' => "text-align:left;"),
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
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
                        <h2 style="text-transform: uppercase;">Current Month Overview</h2>
                    </header>
                    <!-- widget div-->
                    <div>
                        <!-- widget content -->
                        <div class="widget-body padding-10">
                            <!--                        <div class="row">-->
                            <!--                            <div class="col-lg-6 col-md-6 col-sm-12 chart-border">-->
                            <!--                                <div id="incomeChart" style="height: 400px"></div>-->
                            <!--                            </div>-->
                            <!--                            <div class="col-lg-6 col-md-6 col-sm-12 chart-border">-->
                            <!--                                <div id="expenseChart" style="height: 400px"></div>-->
                            <!--                            </div>    -->
                            <!--                        </div>-->
                            <!--                        <div class="row margin-top-10">-->
                            <!--                            <div class="col-lg-12 col-md-12 col-sm-12 chart-border">-->
                            <!--                                <div id="netWorthChart" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>-->
                            <!--                            </div>-->
                            <!--                        </div>                        -->
                            <!--                        <div class="row chart-border margin-top-10">-->
                            <!--                            <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12 show-stats">-->
                            <!--                                <h1 class="text-center">Budget Balance</h1>-->
                            <!--                                --><?php //TransactionBudget::getBudgetChart(); ?>
                            <!--                            </div>-->
                            <!--                        </div>-->
                            <!--                        <div class="row margin-top-10">-->
                            <!--                            <div class="col-lg-12 col-md-12 col-sm-12 chart-border">-->
                            <!--                                <div id="balanceChart" style="height: 400px; margin: 0 auto;"></div>-->
                            <!--                            </div>-->
                            <!--                        </div>                        -->
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
        <!-- row -->
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
                        <h2 style="text-transform: uppercase;">Hierarchical Tag Report</h2>
                        <div class="pull-right" style="padding: 8px 15px;">
                            <form class="smart-form" action="" method="get" style="display: inline;">
                                <select name="month" class="form-control input-xs" style="width: auto; display: inline;" onchange="this.form.submit()">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo $m; ?>" <?php echo $selectedMonth == $m ? 'selected="selected"' : ''; ?>>
                                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <select name="year" class="form-control input-xs" style="width: auto; display: inline;" onchange="this.form.submit()">
                                    <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                                        <option value="<?php echo $y; ?>" <?php echo $selectedYear == $y ? 'selected="selected"' : ''; ?>>
                                            <?php echo $y; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </form>
                        </div>
                    </header>
                    <div>
                        <div class="widget-body no-padding">
                            <?php if (!empty($treeData)): ?>
                                <table class="table table-bordered table-striped table-hover smart-form">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">Tag</th>
                                            <th style="text-align: right;">Expense</th>
                                            <th style="text-align: right;">Income</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($treeData as $node): ?>
                                            <tr class="tree-node level-<?php echo $node['level']; ?>">
                                                <td style="text-align: left; padding-left: <?php echo ($node['level'] * 20 + 5); ?>px;">
                                                    <?php if (!empty($node['children'])): ?>
                                                        <i class="fa fa-folder-open" style="color: #f0ad4e;"></i>
                                                    <?php else: ?>
                                                        <i class="fa fa-tag" style="color: #337ab7;"></i>
                                                    <?php endif; ?>
                                                    <?php echo CHtml::encode($node['tag_name']); ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php echo Transaction::get_amount(1, $node['expense_total']); ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php echo Transaction::get_amount(2, $node['income_total']); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="padding-10 text-center">No tag data available for the selected period.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <!-- end row -->
    </section>
    <!-- end widget grid -->
<?php /*
<script type="text/javascript">
    $(function () {
        $('#expenseChart').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Expense in this month'
            },
            tooltip: {
                pointFormat: '{series.name} expense <b>{point.y:,.0f}</b><br/>Percentage <b>{point.percentage:.1f}%</b>'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 50,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: <b>{point.y:,.0f}</b> {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || '#666'
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Tag',
                    data: [<?php echo Transaction::dashboardExpenseChart(); ?>]
                }]
        });
        $('#incomeChart').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Income in this month'
            },
            tooltip: {
                pointFormat: '{series.name} income <b>{point.y:,.0f}</b><br/>Percentage <b>{point.percentage:.1f}%</b>'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 50,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: <b>{point.y:,.0f}</b> {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || '#666'
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Tag',
                    data: [<?php echo Transaction::dashboardIncomeChart(); ?>]
                }]
        });
        $('#balanceChart').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45
                }
            },
            title: {
                text: 'Account balance in this month'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    innerSize: 100,
                    depth: 45
                }
            },
            series: [{
                    name: 'Balance',
                    data: [<?php echo Transaction::dashboardBalanceChart(); ?>]
                }]
        });
        $('#netWorthChart').highcharts({
            title: {
                text: 'Net Worth Last Twelve Months',
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
            credits: {
                enabled: false
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                    name: 'Net Worth',
                    data: [<?php echo Transaction::accountWorthComparisonChart(); ?>]
                }]
        });
    });
</script>
 */ ?>