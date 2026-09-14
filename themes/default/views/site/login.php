<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = 'Online money management software for personal finance, account aggregation, budgeting, bill reminders, forecasting and splitting bills with friends - ' . Yii::app()->name;
?>
<?php $this->renderPartial('_register', array('registration' => $registration)); ?>
<?php $this->renderPartial('_contact', array('contact' => $contact)); ?>
<div id="content" class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
            <?php User::get_alert_message(); ?>
            <h1 class="txt-color-red login-header-big">MAKE WISER SPENDING DECISIONS</h1>
            <div class="hero">
                <div class="pull-left login-desc-box-l">
                    <h4 class="paragraph-header"><?php echo CHtml::encode(Yii::app()->name); ?> helps you see all your accounts at one place, understand where your money goes, reduce unwanted spending, and save for future goals.</h4>
                    <div class="login-app-icons">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i> Free Account', 'javascript:void(0);', array('onclick' => 'renderCreateForm();', 'class' => 'btn btn-danger btn-sm')); ?>
                        <?php echo CHtml::link('<i class="fa fa-envelope"></i> Contact Us', 'javascript:void(0);', array('onclick' => 'renderCreateForms();', 'class' => 'btn btn-warning btn-sm')); ?>
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
        <div class="col-lg-4 col-md-5 col-sm-12">            
            <div class="well no-padding">                
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'htmlOptions' => array('id' => 'login-form', 'class' => 'smart-form client-form'),
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                ?>
                <header>
                    Sign In
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-12">
                            <?php echo $form->errorSummary($model, '<i class="fa fa-bell text-danger"></i> Please fix the following input errors:', '', array('class' => 'text-danger', 'style' => 'padding-left:20px;')); ?>
                        </section>
                    </div>
                    <section>
                        <label class="label">E-mail</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $form->textField($model, 'username'); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
                    </section>
                    <section>
                        <label class="label">Password</label>
                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                            <?php echo $form->passwordField($model, 'password'); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
                        <div class="note">
                            <?php echo CHtml::link('Forgot password?', array('/recovery/recovery')); ?>
                        </div>
                    </section>
                    <section>        
                        <label class="checkbox">
                            <?php echo $form->checkBox($model, 'rememberMe', array('checked' => 'checked')); ?>
                            <i></i><?php echo $form->label($model, 'rememberMe', array()); ?></label>
                    </section>
                </fieldset>
                <footer>
                    <?php echo CHtml::submitButton('Sign in', array('class' => 'btn btn-primary')); ?>
                </footer>
                <?php $this->endWidget(); ?>
            </div>
            <h5 class="text-center"> - Or sign in using -</h5>
            <ul class="list-inline text-center">
                <li>
                    <a href="javascript:void(0);" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-linkedin"></i></a>
                </li>
            </ul>
        </div>        
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline">
                        <li><?php echo CHtml::link('FEATURES', array('content/view', 'id' => 1), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                        <li><?php echo CHtml::link('ABOUT US', array('content/view', 'id' => 2), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                        <li><?php echo CHtml::link('HELP', array('content/view', 'id' => 3), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                        <li><?php echo CHtml::link('PRICING', array('content/view', 'id' => 4), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                        <li><?php echo CHtml::link('PRIVACY', array('content/view', 'id' => 5), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                        <li><?php echo CHtml::link('TERMS', array('content/view', 'id' => 6), array('class' => '', 'data-toggle' => 'modal', 'data-target' => '#registerModal')); ?></li>
                    </ul>                    
                </div>                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span class="">Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::app()->name; ?>. Developed by <?php echo CHtml::link('Optimo Solution', 'http://www.optimosolution.com', array('target' => '_blank')); ?></span>
                </div>
            </div>	
        </div>        
    </div>
</div>