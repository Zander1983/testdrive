<?php
/* @var $this ArticleController */
/* @var $data Article */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pushed')); ?>:</b>
	<?php echo CHtml::encode($data->pushed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number_pushed_to')); ?>:</b>
	<?php echo CHtml::encode($data->number_pushed_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_created')); ?>:</b>
	<?php echo CHtml::encode($data->time_created); ?>
	<br />


</div>