<?php
/* @var $this TagController */
/* @var $model Tag */
$this->pageTitle = 'Tag Details - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Tags' => array('admin'),
    $model->tag_name,
);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newTagLabel">TAG DETAILS</h4>
</div>
<div class="modal-body">
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array('class' => 'table table-striped table-hover'),
        'data' => $model,
        'attributes' => array(
            array(
                'name' => 'parent_tag',
                'type' => 'raw',
                'value' => Tag::get_tag($model->parent_tag),
            ),
            'tag_name',
            array(
                'name' => 'created',
                'type' => 'raw',
                'value' => UserAdmin::get_date($model->created),
            ),
            array(
                'name' => 'modified',
                'type' => 'raw',
                'value' => UserAdmin::get_date($model->modified),
            ),
        ),
    ));
    ?>  
</div>