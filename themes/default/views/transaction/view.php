<?php
/* @var $this TransactionController */
/* @var $model Transaction */

$this->breadcrumbs = array(
    'Transactions' => array('index'),
    $model->id,
);
?>

<h1>View Transaction #<?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'user',
        'description',
        'amount',
        'created',
        'modified',
        'transaction_type',
        'account',
        'tag',
        'group',
        'status',
        'notes',
    ),
));
?>
