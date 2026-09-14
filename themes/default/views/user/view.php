<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'My Profile - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Profile' => array('view', 'id' => $model->id),
    $model->name,
);
Yii::app()->clientScript->registerScript('reload-pageSetUp', "
    function reloadPageSetUp() {
        pageSetUp();
    }
    ", CClientScript::POS_END);
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts-3d.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i> 
            My Profile
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
                    <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                    <h2>My Profile</h2>
                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">		
                            <a data-toggle="tab" href="#s1"><i class="fa fa-home"></i> <span class="hidden-mobile hidden-tablet">PROFILE</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s2"><i class="fa fa-key"></i> <span class="hidden-mobile hidden-tablet">PASSWORD</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s3"><i class="fa fa-bell"></i> <span class="hidden-mobile hidden-tablet">ALERTS</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s4"><i class="fa fa-cog"></i> <span class="hidden-mobile hidden-tablet">SETTINGS</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s5"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">MEMBERSHIP</span></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#s6"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">AUTOMATIC RULES</span></a>
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
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'user-update-form',
                                    'enableAjaxValidation' => false,
                                    'htmlOptions' => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'),
                                ));
                                ?>   
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model, 'name'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model, 'name', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Name')); ?>
                                            <?php echo $form->error($model, 'name', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model, 'email'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model, 'email', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
                                            <?php echo $form->error($model, 'email', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'country_id'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->dropDownList($model_profile, 'country_id', CHtml::listData(Country::model()->findAll(array('condition' => 'published=1')), 'id', 'country_name'), array('placeholder' => 'Country', 'class' => 'select2')); ?>
                                            <?php echo $form->error($model_profile, 'country_id', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'state_id'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->dropDownList($model_profile, 'state_id', CHtml::listData(State::model()->findAll(array('condition' => 'published=1')), 'id', 'state_name'), array('placeholder' => 'State', 'class' => 'select2')); ?>
                                            <?php echo $form->error($model_profile, 'state_id', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'city_id'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->dropDownList($model_profile, 'city_id', CHtml::listData(City::model()->findAll(array('condition' => 'published=1')), 'id', 'city_name'), array('placeholder' => 'City', 'class' => 'select2')); ?>
                                            <?php echo $form->error($model_profile, 'city_id', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'address'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'address', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Address')); ?>
                                            <?php echo $form->error($model_profile, 'address', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'mobile'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'mobile', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Mobile')); ?>
                                            <?php echo $form->error($model_profile, 'mobile', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'phone'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'phone', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Phone')); ?>
                                            <?php echo $form->error($model_profile, 'phone', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'fax'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'fax', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Fax')); ?>
                                            <?php echo $form->error($model_profile, 'fax', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'website'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'website', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Website')); ?>
                                            <?php echo $form->error($model_profile, 'website', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'birth_date'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->textField($model_profile, 'birth_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')); ?>    
                                            <?php echo $form->error($model_profile, 'birth_date', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'gender'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->dropDownList($model_profile, 'gender', array('1' => 'Male', '0' => 'Female'), array('class' => 'select2')); ?>
                                            <?php echo $form->error($model_profile, 'gender', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form->labelEx($model_profile, 'profile_picture'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form->fileField($model_profile, 'profile_picture', array('class' => 'btn btn-default')); ?>
                                            <p class="help-block">
                                                <?php echo $form->error($model_profile, 'profile_picture'); ?>
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="modal-footer">   
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary')); ?>
                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                </div>
                                <?php $this->endWidget(); ?>                                  
                            </div>
                            <!-- end s1 tab pane -->
                            <div class="tab-pane fade padding-10" id="s2">
                                <?php
                                $form_access = $this->beginWidget('CActiveForm', array(
                                    'id' => 'access-form',
                                    'enableAjaxValidation' => false,
                                    'htmlOptions' => array('class' => 'form-horizontal'),
                                ));
                                ?>                         
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form_access->labelEx($model_access, 'password'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form_access->passwordField($model_access, 'password', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
                                            <?php echo $form_access->error($model_access, 'password', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form_access->labelEx($model_access, 'verifypassword'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form_access->passwordField($model_access, 'verifypassword', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Confirm password')); ?>
                                            <?php echo $form_access->error($model_access, 'verifypassword', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>                                       
                                </fieldset>
                                <div class="modal-footer">   
                                    <?php echo CHtml::submitButton('CHANGE PASSWORD', array('class' => 'btn btn-primary')); ?>
                                </div>
                                <?php $this->endWidget(); ?>     
                            </div>
                            <!-- end s2 tab pane -->
                            <div class="tab-pane fade padding-10" id="s3">
                                ALERTS 
                            </div>
                            <!-- end s3 tab pane -->
                            <div class="tab-pane fade padding-10" id="s4">
                                <?php
                                $form_setting = $this->beginWidget('CActiveForm', array(
                                    'id' => 'setting-form',
                                    'enableAjaxValidation' => false,
                                    'htmlOptions' => array('class' => 'form-horizontal'),
                                ));
                                ?>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form_setting->labelEx($model_setting, 'currency'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form_setting->dropDownList($model_setting, 'currency', CHtml::listData(Currency::model()->findAll(array('condition' => 'published=1')), 'id', 'currency_name'), array('placeholder' => 'Currency', 'class' => 'select2')); ?>
                                            <?php echo $form_setting->error($model_setting, 'currency', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form_setting->labelEx($model_setting, 'Currency_format'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form_setting->dropDownList($model_setting, 'Currency_format', array('1' => '10,000.00', '2' => '10,000.00', '3' => '10.000,00', '4' => '10.000,00'), array('class' => 'select2')); ?>            
                                            <?php echo $form_setting->error($model_setting, 'Currency_format', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">
                                            <?php echo $form_setting->labelEx($model_setting, 'Timezone'); ?>
                                        </label>
                                        <div class="col-md-8">
                                            <?php echo $form_setting->dropDownList($model_setting, 'Timezone', CHtml::listData(Timezone::model()->findAll(array('condition' => '')), 'id', 'title'), array('placeholder' => 'Time Zone', 'class' => 'select2')); ?>
                                            <?php echo $form_setting->error($model_setting, 'Timezone', array('class' => 'text-danger')); ?>
                                        </div>
                                    </div>    
                                </fieldset>
                                <div class="modal-footer">   
                                    <?php echo CHtml::submitButton('SAVE SETTINGS', array('class' => 'btn btn-primary')); ?>
                                </div>
                                <?php $this->endWidget(); ?>
                            </div>
                            <!-- end s4 tab pane -->
                            <div class="tab-pane fade padding-10" id="s5">
                                MEMBERSHIP 
                            </div>
                            <!-- end s5 tab pane -->
                            <div class="tab-pane fade padding-10" id="s6">
                                AUTOMATIC RULES 
                            </div>
                            <!-- end s5 tab pane -->
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
</section>
<!-- end widget grid -->
<div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="editSettings" tabindex="-1" role="dialog" aria-labelledby="editSettingsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->