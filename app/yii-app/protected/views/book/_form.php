<?php
/* @var $this BookController */
/* @var $model Book */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isbn'); ?>
		<?php echo $form->textField($model,'isbn',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'isbn'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cover_image'); ?>
		<?php echo $form->fileField($model,'cover_image'); ?>
		<?php echo $form->error($model,'cover_image'); ?>
		<?php if (!$model->isNewRecord && $model->cover_image): ?>
			<br/>
			<?php echo CHtml::image(Yii::app()->baseUrl.'/images/covers/'.$model->cover_image, 'Cover', array('width'=>100)); ?>
		<?php endif; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_ids'); ?>
		<?php echo $form->checkBoxList($model,'author_ids', CHtml::listData(Author::model()->findAll(), 'id', 'full_name')); ?>
		<?php echo $form->error($model,'author_ids'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
