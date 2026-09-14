<?php
/* @var $this TransactionContactController */
/* @var $model TransactionContact */
$this->pageTitle = 'Contact Details - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Contacts' => array('admin'),
    $model->id,
);
?>
<h1>Contact Details: <?php echo $model->contact_name; ?></h1>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'contact_name',
        'email',
    ),
));
?>
