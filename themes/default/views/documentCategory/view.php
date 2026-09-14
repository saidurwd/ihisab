<?php
$this->breadcrumbs=array(
	'Document Categories'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List DocumentCategory','url'=>array('index')),
	array('label'=>'Create DocumentCategory','url'=>array('create')),
	array('label'=>'Update DocumentCategory','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete DocumentCategory','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DocumentCategory','url'=>array('admin')),
);
?>

<h1>View DocumentCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent',
		'title',
		'description',
		'published',
		'created_by',
		'created_time',
		'modified_by',
		'modified_time',
	),
)); ?>
