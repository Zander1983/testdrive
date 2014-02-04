<?php
/* @var $this ArticleController */
/* @var $data Article */


?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php 
            $user = User::model()->findByPk(Yii::app()->user->id);
            if($user->isAdmin()){
                echo CHtml::encode($data->user_id);                
            }
            else{
                
            }
        ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />
        
        
	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php
              echo Project::model()->findByPk($data->project_id)->project_title;  
                ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('android_response')); ?>:</b>
	<?php echo CHtml::encode($data->android_response); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apple_response')); ?>:</b>
	<?php echo CHtml::encode($data->apple_response); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_created')); ?>:</b>
	<?php 
        echo CHtml::encode(date('Y-m-d H:i:s', $data->time_created)); 
        ?>
	<br />


</div>