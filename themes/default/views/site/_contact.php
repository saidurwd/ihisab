<!-- CREATE Modal -->
<div class="modal fade" id="modalCreates" tabindex="-1" role="dialog" aria-labelledby="modalCreatesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreatesLabel"><i class="fa fa-envelope-o"></i> CONTACT US</h4>
            </div>          
            <div class="modal-body"> 
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'contact-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'method' => 'post',
                    'action' => array("site/contact"),
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'onsubmit' => "return false;", /* Disable normal form submit */
                        'onkeypress' => " if(event.keyCode == 13){ creates(); } " /* Do ajax call when user presses enter key */
                    ),
                    'clientOptions' => array(
                        'validateOnType' => true,
                        'validateOnSubmit' => true,
                        'afterValidate' => 'js:function(form, data, hasError) {if (!hasError){ creates(); }}'
                    ),
                ));
                ?>
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($contact, 'subject'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($contact, 'subject', array('maxlength' => 250, 'class' => 'form-control', 'placeholder' => 'Subject')); ?>
                            <?php echo $form->error($contact, 'subject'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($contact, 'name'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($contact, 'name', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Name')); ?>
                            <?php echo $form->error($contact, 'name'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($contact, 'email'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($contact, 'email', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
                            <?php echo $form->error($contact, 'email'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($contact, 'body'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textArea($contact, 'body', array('maxlength' => 1000, 'class' => 'form-control', 'placeholder' => 'Summary')); ?>
                            <?php echo $form->error($contact, 'body'); ?>
                        </div>
                    </div>
                    <?php if (CCaptcha::checkRequirements()): ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label">
                                <?php echo $form->labelEx($contact, 'verifyCode'); ?>
                            </label>
                            <div class="col-md-8">
                                <?php $this->widget('CCaptcha'); ?>
                                <?php echo $form->textField($contact, 'verifyCode', array('size' => 20, 'maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Verification Code')); ?>
                                <?php echo $form->error($contact, 'verifyCode'); ?>
                                <p class="note"><strong>Note:</strong> Please enter the letters as they are shown in the image above. Letters are not case-sensitive.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </fieldset>   
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>      
                            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div><!-- form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    function creates()
    {
        var data = $("#contact-form").serialize();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/contact"); ?>',
            data: data,
            success: function (data) {
                //alert("succes:"+data); 
                if (data != "false")
                {
                    $('#modalCreates').modal('hide');
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

    function renderCreateForms()
    {
        $('#contact-form').each(function () {
            this.reset();
        });
        $('#modalCreates').modal({
            show: true,
        });
    }
</script>