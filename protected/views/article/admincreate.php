<?php
/* @var $this ArticleController */
/* @var $model Article */

$this->breadcrumbs=array(
	'Articles'=>array('index'),
	'Create',
);

/*
$this->menu=array(
	array('label'=>'List Article', 'url'=>array('index')),
	array('label'=>'Manage Article', 'url'=>array('admin')),
);*/
?>

<h1>Send Message</h1>

<?php $this->renderPartial('_admincreateform', array('model'=>$model)); ?>