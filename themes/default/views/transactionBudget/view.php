<?php
/* @var $this TransactionBudgetController */
/* @var $model TransactionBudget */
$this->pageTitle = 'Budget Details - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transaction Budgets' => array('admin'),
    $model->tag_name,
);
?>
<h1>Budget Details: <?php echo $model->tag_name; ?></h1>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'tag_name',
        'amount',
        array(
            'name' => 'created',
            'type' => 'raw',
            'value' => date("F j, Y, g:i A", strtotime($model->created)),
        ),
        array(
            'name' => 'modified',
            'type' => 'raw',
            'value' => date("F j, Y, g:i A", strtotime($model->modified)),
        ),
    ),
));
?>
