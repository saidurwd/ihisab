<?php
/* @var $this AccountController */
/* @var $model Account */
$this->pageTitle = 'Account Details - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Accounts' => array('admin'),
    $model->account_name,
);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newAccountLabel">ACCOUNT DETAILS</h4>
</div>
<div class="modal-body">
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array('class' => 'table table-striped table-hover'),
        'data' => $model,
        'attributes' => array(
            'account_name',
            'institution',
            array(
                'name' => 'account_type',
                'type' => 'raw',
                'value' => AccountType::get_type($model->account_type),
            ),
            array(
                'name' => 'currency',
                'type' => 'raw',
                'value' => Currency::get_currency($model->currency),
            ),
        ),
    ));
    ?>   
</div>