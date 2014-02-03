<?php
/* @var $this ArticleController */
/* @var $model Article */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 

    $form=$this->beginWidget('CActiveForm', array(
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
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>20, 'cols' => 50, 'maxlength'=>2000)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>



	<div class="row buttons">
		<?php 
                
                $alert = "Are you sure you want to send this Push Notification? $notifcation_on devices will be sent this message!";
                
                echo CHtml::submitButton('Send Message', 
                                        array('confirm' => $alert)); 
              
                /*
                CHtml::link(
                    'Delete',
                     array('confirm' => 'Are you sure you want to send this Push Notification?')
                );*/
                
                ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->