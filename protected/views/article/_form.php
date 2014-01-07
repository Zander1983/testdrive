<?php
/* @var $this ArticleController */
/* @var $model Article */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'article-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'article_url'); ?>
		<?php echo $form->textField($model,'article_url',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'article_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pushed'); ?>
		<?php echo $form->textField($model,'pushed'); ?>
		<?php echo $form->error($model,'pushed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number_pushed_to'); ?>
		<?php echo $form->textField($model,'number_pushed_to'); ?>
		<?php echo $form->error($model,'number_pushed_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_created'); ?>
		<?php echo $form->textField($model,'time_created'); ?>
		<?php echo $form->error($model,'time_created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->