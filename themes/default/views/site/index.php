<?php
/* @var $this SiteController */
$this->pageTitle = 'Dashboard - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Dashboard',
);
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts-3d.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
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
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
                    <h2 style="text-transform: uppercase;">Hierarchical Tag Report</h2>
                    <div class="pull-right" style="padding: 1px 15px;">
                        <form class="smart-form" action="<?php echo Yii::app()->createUrl('site/index'); ?>"
                            method="get" style="display: inline;">
                            <select name="month" class="form-control input-xs" style="width: auto; display: inline;"
                                onchange="this.form.submit()">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo $selectedMonth == $m ? 'selected="selected"' : ''; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <select name="year" class="form-control input-xs" style="width: auto; display: inline;"
                                onchange="this.form.submit()">
                                <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $selectedYear == $y ? 'selected="selected"' : ''; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </form>
                        <button type="button" class="btn btn-xs btn-default" id="btn-expand-all">Expand All</button>
                        <button type="button" class="btn btn-xs btn-default" id="btn-collapse-all">Collapse All</button>
                    </div>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <style>
                            .tree-child {
                                display: none;
                            }

                            .tree-toggle {
                                cursor: pointer;
                                display: inline-block;
                                width: 16px;
                                text-align: center;
                                margin-right: 4px;
                            }
                        </style>
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
                                    <?php foreach ($treeData as $node):
                                        $isRoot = empty($node['parent_tag']) || $node['parent_tag'] == 0;
                                        $hasChildren = !empty($node['children']);
                                        $rowClass = 'tree-node';
                                        if (!$isRoot) {
                                            $rowClass .= ' tree-child';
                                        }
                                        if ($hasChildren) {
                                            $rowClass .= ' tree-parent';
                                        }
                                        ?>
                                        <tr class="<?php echo $rowClass; ?>" data-id="<?php echo $node['id']; ?>"
                                            data-parent="<?php echo $node['parent_tag']; ?>">
                                            <td
                                                style="text-align: left; padding-left: <?php echo ($node['level'] * 20 + 5); ?>px;">
                                                <?php if ($hasChildren): ?>
                                                    <span class="tree-toggle">
                                                        <i class="fa fa-plus-square"></i>
                                                    </span>
                                                    <i class="fa fa-folder" style="color: #f0ad4e;"></i>
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
            <script>
                $(document).ready(function () {
                    $('.tree-child').hide();

                    var parentChildrenMap = {};
                    $('.tree-node').each(function () {
                        var $row = $(this);
                        var parentId = $row.data('parent');
                        var id = $row.data('id');
                        if (parentId) {
                            if (!parentChildrenMap[parentId]) {
                                parentChildrenMap[parentId] = [];
                            }
                            parentChildrenMap[parentId].push(id);
                        }
                    });

                    $('.tree-toggle').click(function (e) {
                        e.stopPropagation();
                        var $parentRow = $(this).closest('.tree-node');
                        var parentId = $parentRow.data('id');
                        var $icon = $(this).find('i');
                        var isExpanded = $parentRow.hasClass('tree-expanded');

                        if (isExpanded) {
                            collapseNode(parentId, parentChildrenMap);
                            $parentRow.removeClass('tree-expanded');
                            $icon.removeClass('fa-minus-square').addClass('fa-plus-square');
                            $parentRow.find('.fa-folder-open').removeClass('fa-folder-open').addClass('fa-folder');
                        } else {
                            expandNode(parentId, parentChildrenMap);
                            $parentRow.addClass('tree-expanded');
                            $icon.removeClass('fa-plus-square').addClass('fa-minus-square');
                            $parentRow.find('.fa-folder').removeClass('fa-folder').addClass('fa-folder-open');
                        }
                    });

                    $('#btn-expand-all').click(function () {
                        $('.tree-parent').each(function () {
                            var $row = $(this);
                            if (!$row.hasClass('tree-expanded')) {
                                $row.find('.tree-toggle').click();
                            }
                        });
                    });

                    $('#btn-collapse-all').click(function () {
                        $('.tree-parent').each(function () {
                            var $row = $(this);
                            if ($row.hasClass('tree-expanded')) {
                                $row.find('.tree-toggle').click();
                            }
                        });
                    });
                });

                function expandNode(parentId, map) {
                    if (map[parentId]) {
                        $.each(map[parentId], function (i, childId) {
                            var $childRow = $('.tree-node[data-id="' + childId + '"]');
                            $childRow.show();
                            if ($childRow.hasClass('tree-parent')) {
                                $childRow.addClass('tree-expanded');
                                $childRow.find('.tree-toggle i').removeClass('fa-plus-square').addClass('fa-minus-square');
                                expandNode(childId, map);
                            }
                        });
                    }
                }

                function collapseNode(parentId, map) {
                    if (map[parentId]) {
                        $.each(map[parentId], function (i, childId) {
                            var $childRow = $('.tree-node[data-id="' + childId + '"]');
                            $childRow.hide();
                            if ($childRow.hasClass('tree-parent')) {
                                $childRow.removeClass('tree-expanded');
                                $childRow.find('.tree-toggle i').removeClass('fa-minus-square').addClass('fa-plus-square');
                                collapseNode(childId, map);
                            }
                        });
                    }
                }
            </script>
        </article>
    </div>
    <!-- end row -->
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
                    <h2 style="text-transform: uppercase;">Income vs. Expenses by Year</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php Transaction::getYearlyIncomeExpanse(); ?>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->ß
</section>
<!-- end widget grid -->