<?php
/* @var $this SubscriptionController */
/* @var $model Subscription */
/* @var $author Author */

$this->breadcrumbs=array(
	'Authors'=>array('author/index'),
	$author->full_name=>array('author/view','id'=>$author->id),
	'Subscribe',
);

$this->menu=array(
	array('label'=>'Back to Author', 'url'=>array('author/view', 'id'=>$author->id)),
);
?>

<h1>Подписка на автора: <?php echo CHtml::encode($author->full_name); ?></h1>

<p>Введите ваш номер телефона для получения уведомлений о новых книгах этого автора.</p>

<?php if(Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subscription-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20, 'placeholder'=>'+79991234567')); ?>
		<?php echo $form->error($model,'phone'); ?>
		<p class="hint">Формат: +79991234567 или 79991234567</p>
	</div>

	<?php echo $form->hiddenField($model,'author_id'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Подписаться'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
