<?php
/* @var $this UserController */
/* @var $model EmailPreference */
$this->pageTitle = 'Email Preferences - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Email Preferences',
);
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-envelope fa-fw"></i>
            Email Preferences
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="jarviswidget" id="wid-id-email-pref" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-envelope"></i> </span>
                <h2>Daily Transaction Summary</h2>
            </header>
            <div>
                <div class="widget-body no-padding">
                    <?php if (Yii::app()->user->hasFlash('success')): ?>
                        <div class="alert alert-success">
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo CHtml::beginForm(); ?>
                    <div class="padding-10">
                        <div class="form-group">
                            <label class="control-label">Enable Daily Summary Emails</label>
                            <div class="checkbox">
                                <label>
                                    <?php echo CHtml::checkBox('EmailPreference[enabled]', $model->enabled == 1, array('value' => 1, 'uncheckValue' => 0)); ?>
                                    Send me a daily email summary of my transactions
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Frequency</label>
                            <?php echo CHtml::activeDropDownList($model, 'frequency', array(
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                                'never' => 'Never',
                            ), array('class' => 'form-control')); ?>
                        </div>

                        <div class="form-actions">
                            <?php echo CHtml::submitButton('Save Preferences', array('class' => 'btn btn-primary')); ?>
                        </div>
                    </div>
                    <?php echo CHtml::endForm(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
