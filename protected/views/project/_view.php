<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tbl_project_number')); ?>:</b>
	<?php echo CHtml::encode($data->tbl_project_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_key')); ?>:</b>
	<?php echo CHtml::encode($data->api_key); ?>
	<br />


</div>