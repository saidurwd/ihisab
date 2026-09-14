<!-- CREATE Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreateLabel"><i class="fa fa-plus"></i> REGISTRATION</h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'method' => 'post',
                'action' => array("user/register"),
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'onsubmit' => "return false;", /* Disable normal form submit */
                    'onkeypress' => " if(event.keyCode == 13){ create(); } " /* Do ajax call when user presses enter key */
                ),
                'clientOptions' => array(
                    'validateOnType' => true,
                    'validateOnSubmit' => true,
                    'afterValidate' => 'js:function(form, data, hasError) {if (!hasError){ create(); }}'
                ),
            ));
            ?>
            <div class="modal-body"> 
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($registration, 'name'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($registration, 'name', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Full Name')); ?>
                            <?php echo $form->error($registration, 'name', array('class' => 'text-danger')); ?>           
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($registration, 'username'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($registration, 'username', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Username')); ?>
                            <?php echo $form->error($registration, 'username', array('class' => 'text-danger')); ?>
                            <b class="tooltip tooltip-bottom-right">Needed to enter the application</b>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($registration, 'email'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($registration, 'email', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Email address')); ?>
                            <?php echo $form->error($registration, 'email', array('class' => 'text-danger')); ?>
                            <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($registration, 'password'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->passwordField($registration, 'password', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
                            <?php echo $form->error($registration, 'password', array('class' => 'text-danger')); ?>
                            <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($registration, 'passwordConfirm'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->passwordField($registration, 'passwordConfirm', array('maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Confirm password')); ?>
                            <?php echo $form->error($registration, 'passwordConfirm', array('class' => 'text-danger')); ?>
                            <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                        </div>
                    </div>
                </fieldset>     
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>      
                            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    function create()
    {
        var data = $("#user-form").serialize();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("user/register"); ?>',
            data: data,
            success: function (data) {
                //alert("succes:"+data); 
                if (data != "false")
                {
                    $('#modalCreate').modal('hide');
                    $("#content").load('<?php echo Yii::app()->getRequest()->getUrl(); ?> #content');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                alert(data);
            },
            dataType: 'html'
        });
    }

    function renderCreateForm()
    {
        $('#user-form').each(function () {
            this.reset();
        });
        //$('#modalCreate').modal('hide');
        $('#modalCreate').modal({
            show: true,
        });
    }
</script>