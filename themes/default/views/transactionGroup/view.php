<?php
/* @var $this TransactionGroupController */
/* @var $model TransactionGroup */

$this->breadcrumbs = array(
    'Transaction Groups' => array('admin'),
    $model->title,
);
?>

<h1>View TransactionGroup #<?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'int',
        'user',
        'title',
        'members',
    ),
));
?>
