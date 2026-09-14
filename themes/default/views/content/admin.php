<?php
$this->breadcrumbs = array(
    'Contents' => array('index'),
    'Manage',
);
$this->menu = array(
    array('label' => 'Home', 'icon' => 'home', 'url' => array('#'), 'active' => true),
    array('label' => 'Library', 'icon' => 'book', 'url' => array('#')),
    array('label' => 'Application', 'icon' => 'pencil', 'url' => array('#')),
    array('label' => 'Profile', 'icon' => 'user', 'url' => array('#')),
    array('label' => 'Settings', 'icon' => 'cog', 'url' => array('#')),
    array('label' => 'Help', 'icon' => 'flag', 'url' => array('#')),
);
?>

<h1>Manage Contents</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'content-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'title',
        'alias',
        'introtext',
        'fulltext',
        'state',
        /*
          'catid',
          'created',
          'created_by',
          'modified',
          'modified_by',
          'publish_up',
          'publish_down',
          'ordering',
          'metakey',
          'metadesc',
          'hits',
          'featured',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
