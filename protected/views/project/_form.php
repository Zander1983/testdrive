<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'project_title'); ?>
		<?php echo $form->textField($model,'project_title',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'project_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project_number'); ?>
		<?php echo $form->textField($model,'project_number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'project_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'api_key'); ?>
		<?php echo $form->textField($model,'api_key',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'api_key'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->