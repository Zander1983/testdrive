<?php
/* @var $this ArticleController */
/* @var $data Article */
?>


<div class="view">
    
	<b><?php echo CHtml::encode($data->getAttributeLabel('project_title')); ?>:</b>
	<?php echo CHtml::encode($data->project_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('platform')); ?>:</b>
	<?php echo CHtml::encode($data->platform); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notification_on')); ?>:</b>
	<?php echo CHtml::encode($data->notification_on); ?>
	<br />


</div>