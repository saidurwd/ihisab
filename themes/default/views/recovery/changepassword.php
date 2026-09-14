<?php
$this->pageTitle = 'Change Password - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Change Password',
);
?>
<div id="content" class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
            <?php User::get_alert_message(); ?>
            <h1 class="txt-color-red login-header-big">MAKE WISER SPENDING DECISIONS</h1>
            <div class="hero">
                <div class="pull-left login-desc-box-l">
                    <h4 class="paragraph-header"><?php echo CHtml::encode(Yii::app()->name); ?> helps you see all your accounts at one place, understand where your money goes, reduce unwanted spending, and save for future goals.</h4>
                    <div class="login-app-icons">
                        <?php echo CHtml::link('FREE ACCOUNT', array('user/register'), array('class' => 'btn btn-warning btn-lg')); ?>
                    </div>
                </div>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h5 class="about-heading">ALL ACCOUNTS AT ONE PLACE</h5>
                    <p>Securely enter your data from any bank anywhere in the world. Add transactions on the go. Reconcile them later.</p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h5 class="about-heading">UNDERSTAND WHERE YOUR MONEY GOES</h5>
                    <p>Powerful, visual tools to categorize and understand your spending. Powerful tools that help visualize spending trends and easily spot irregularities.</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
            <div class="well no-padding">
                <?php
                $forms = $this->beginWidget('CActiveForm', array(
                    'id' => 'user-changepassword-form',
                    'enableClientValidation' => true,
                    'htmlOptions' => array('id' => 'user-recovery-form', 'class' => 'smart-form client-form'),
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                ?>
                <header>
                    Change Password
                </header>
                <fieldset>
                    <section>
                        <?php echo $forms->errorSummary($form2, '<i class="fa fa-bell text-danger"></i> Please fix the following input errors:', '', array('class' => 'text-danger', 'style' => 'padding-left:10px;')); ?>
                    </section>
                    <section>
                        <label class="label">Password</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $forms->passwordField($form2, 'password', array('maxlength' => 100)); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter Password</b></label>
                    </section>
                    <section>
                        <label class="label">Verify Password</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $forms->passwordField($form2, 'verifyPassword', array('maxlength' => 100)); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter Verify Password</b></label>
                    </section>
                </fieldset>
                <footer>
                    <?php echo CHtml::submitButton('Change Password', array('class' => 'btn btn-primary')); ?>
                </footer>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline">
                        <li><?php echo CHtml::link('HELP', array('content/view', 'id' => 3), array('class' => '')); ?></li>
                        <li><?php echo CHtml::link('FEATURES', array('content/view', 'id' => 4), array('class' => '')); ?></li>
                        <li><?php echo CHtml::link('PRICING', array('content/view', 'id' => 5), array('class' => '')); ?></li>
                        <li><?php echo CHtml::link('PRIVACY', array('content/view', 'id' => 6), array('class' => '')); ?></li>
                        <li><?php echo CHtml::link('TERMS', array('content/view', 'id' => 7), array('class' => '')); ?></li>
                    </ul>                    
                </div>                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span class="">Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::app()->name; ?></span>
                </div>
            </div>	
        </div>
    </div>
</div>