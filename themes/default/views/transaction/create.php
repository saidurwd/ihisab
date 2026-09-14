<?php
/* @var $this TransactionController */
/* @var $model Transaction */
$this->pageTitle = 'Transaction - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Transactions' => array('admin'),
    'Add Transaction',
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-plus fa-fw "></i> 
            Transactions
            <span>>
                New Transaction
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
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget" id="wid-id-11" data-widget-colorbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false">
    <header>
        <h2><strong>ADD TRANSACTION</strong></h2>	
        <ul id="widget-tab-1" class="nav nav-tabs pull-right">
            <li class="active">
                <a data-toggle="tab" href="#multiple"> <i class="fa fa-lg fa-plus"></i> <span class="hidden-mobile hidden-tablet"> MULTIPLE TRANSACTION </span> </a>
            </li>
            <li>
                <a data-toggle="tab" href="#single"> <i class="fa fa-lg fa-plus"></i> <span class="hidden-mobile hidden-tablet"> SINGLE TRANSACTION </span></a>
            </li>
        </ul>	
    </header>
    <!-- widget div-->
    <div>
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
        </div>
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body no-padding">
            <!-- widget body text-->
            <div class="tab-content padding-10">
                <div class="tab-pane fade in active" id="multiple">
                    <?php //$this->renderPartial('_multiple', array('model' => $model)); ?>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'multiple-transaction-form',
                        'action' => Yii::app()->createUrl('//transaction/multiple'),
                        'enableAjaxValidation' => false,
                            //'htmlOptions' => array('class' => 'smart-form'),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'description', array('class' => 'text-uppercase')); ?></div>
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'amount', array('class' => 'text-uppercase')); ?></div>
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'created', array('class' => 'text-uppercase')); ?></div>
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'transaction_type', array('class' => 'text-uppercase')); ?></div>
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'account', array('class' => 'text-uppercase')); ?></div>
                        <div class="col-md-2"><?php echo $form->labelEx($model, 'tag', array('class' => 'text-uppercase')); ?></div>
                    </div>
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->textField($model, "[$i]description", array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Description')); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->textField($model, "[$i]amount", array('maxlength' => 13, 'class' => 'form-control', 'placeholder' => 'Amount')); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->textField($model, "[$i]created", array('class' => 'form-control datepicker', 'value' => date('Y-m-d'), 'data-dateformat' => 'yy-mm-dd')); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->dropDownList($model, "[$i]transaction_type", CHtml::listData(TransactionType::model()->findAll(array('condition' => 'status=1')), 'id', 'title'), array('class' => 'select2')); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->dropDownList($model, "[$i]account", CHtml::listData(Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND status="Active"')), 'id', 'account_name'), array('options' => array(Account::get_default_account() => array('selected' => true)), 'class' => 'select2')); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <?php echo $form->dropDownList($model, "[$i]tag", CHtml::listData(Tag::model()->findAll(array('condition' => 'user IN(0,' . Yii::app()->user->id . ')', 'order' => 'tag_name')), 'id', 'tag_name', 'parent'), array('placeholder' => 'Tags', 'multiple' => true, 'class' => 'select2')); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="modal-footer">   
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary')); ?>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
                <div class="tab-pane fade" id="single">                    
                    <?php //$this->renderPartial('_single', array('model' => $model)); ?>
                    <?php
                    $form2 = $this->beginWidget('CActiveForm', array(
                        'id' => 'single-transaction-form',
                        'action' => Yii::app()->createUrl('//transaction/single'),
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array('class' => 'form-horizontal'),
                    ));
                    Yii::app()->clientScript->registerScript('show_hide', "  
                            $(document).ready(function(){
                                $('.AccountTo').hide();
                                $('#Transaction_transaction_type').on('change', function() {
                                  if ( this.value == '3')
                                  {
                                    $('.AccountTo').show();
                                  } else {
                                    $('.AccountTo').hide();
                                  }
                                });
                        });
                    ");
                    ?>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'transaction_type'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'transaction_type', CHtml::listData(TransactionType::model()->findAll(array('condition' => 'status=1')), 'id', 'title'), array('class' => 'select2')); ?>
                                <?php echo $form2->error($model, 'transaction_type', array('class' => 'text-danger')); ?>
                            </div>
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'created'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form->textField($model, 'created', array('class' => 'form-control datepicker', 'value' => date('Y-m-d'), 'data-dateformat' => 'yy-mm-dd')); ?>                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'description'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->textField($model, 'description', array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Description')); ?>
                                <?php echo $form2->error($model, 'description', array('class' => 'text-danger')); ?>
                            </div>
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'tag'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'tag', CHtml::listData(Tag::model()->findAll(array('condition' => 'user IN(0,' . Yii::app()->user->id . ')', 'order' => 'tag_name')), 'id', 'tag_name', 'parent'), array('placeholder' => 'Tags', 'multiple' => true, 'class' => 'select2')); ?>
                                <?php echo $form2->error($model, 'tag', array('class' => 'text-danger')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'amount'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->textField($model, 'amount', array('maxlength' => 13, 'class' => 'form-control', 'placeholder' => 'Amount')); ?>
                                <?php echo $form2->error($model, 'amount', array('class' => 'text-danger')); ?>
                            </div>
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'group'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'group', CHtml::listData(TransactionGroup::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id)), 'id', 'title'), array('empty' => 'SELECT', 'class' => 'select2')); ?>
                                <?php echo $form2->error($model, 'group', array('class' => 'text-danger')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'account'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'account', CHtml::listData(Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND status="Active"')), 'id', 'account_name'), array('options' => array(Account::get_default_account() => array('selected' => true)), 'class' => 'select2')); ?>
                                <?php echo $form2->error($model, 'account', array('class' => 'text-danger')); ?>
                            </div>
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'status'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'status', CHtml::listData(TransactionStatus::model()->findAll(array('condition' => 'status=1')), 'id', 'title'), array('class' => 'select2')); ?>
                                <?php echo $form2->error($model, 'status', array('class' => 'text-danger')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'AccountTo', array('class' => 'AccountTo')); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->dropDownList($model, 'AccountTo', CHtml::listData(Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND status="Active"')), 'id', 'account_name'), array('class' => 'select2 AccountTo')); ?>
                                <?php echo $form2->error($model, 'AccountTo', array('class' => 'text-danger AccountTo')); ?>
                            </div>
                            <label class="col-md-2 control-label">
                                <?php echo $form2->labelEx($model, 'notes'); ?>
                            </label>
                            <div class="col-md-4">
                                <?php echo $form2->textField($model, 'notes', array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Notes')); ?>
                                <?php echo $form2->error($model, 'notes', array('class' => 'text-danger')); ?>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">   
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary')); ?>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
            <!-- end widget body text-->
        </div>
        <!-- end widget content -->
    </div>
    <!-- end widget div -->
</div>
<!-- end widget -->