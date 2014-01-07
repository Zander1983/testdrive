<?php
/* @var $this ArticleController */
/* @var $model Article */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'article_url'); ?>
		<?php echo $form->textField($model,'article_url',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pushed'); ?>
		<?php echo $form->textField($model,'pushed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'number_pushed_to'); ?>
		<?php echo $form->textField($model,'number_pushed_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_created'); ?>
		<?php echo $form->textField($model,'time_created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->