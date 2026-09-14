<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="registerModalLabel"><?php echo $model->title; ?></h4>
</div>
<div class="modal-body custom-scroll terms-body">
    <?php echo $model->introtext; ?>
</div>
<div class="modal-footer"> 
    <button data-dismiss="modal" class="btn btn-default" type="button"><i class="fa fa-times"></i> Close</button>
</div>
