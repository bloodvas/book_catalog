<?php
/* @var $this SmsController */
/* @var $model TestSmsForm */

$this->breadcrumbs=array(
	'SMS'=>array('index'),
	'Test',
);

$this->menu=array(
	array('label'=>'Check Balance', 'url'=>array('balance')),
);
?>

<h1>Тестирование SMS</h1>

<p>Отправка тестового SMS сообщения для проверки работы сервиса SMSpilot.</p>

<?php if(Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-sms-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20, 'placeholder'=>'+79991234567')); ?>
		<?php echo $form->error($model,'phone'); ?>
		<p class="hint">Введите номер телефона для отправки тестового сообщения</p>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Отправить тестовое SMS', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<div class="info-box">
	<h4>Информация о текущих настройках:</h4>
	<ul>
		<li><strong>API Key:</strong> <?php echo Yii::app()->smspilot->apiKey; ?></li>
		<li><strong>Отправитель:</strong> <?php echo Yii::app()->smspilot->sender; ?></li>
		<li><strong>Тестовый режим:</strong> <?php echo Yii::app()->smspilot->testMode ? 'Включен' : 'Выключен'; ?></li>
		<li><strong>Endpoint:</strong> <?php echo Yii::app()->smspilot->endpoint; ?></li>
	</ul>
</div>

<style>
.info-box {
	background-color: #f9f9f9;
	border: 1px solid #ddd;
	padding: 15px;
	margin-top: 20px;
	border-radius: 5px;
}

.info-box ul {
	margin: 10px 0;
	padding-left: 20px;
}

.info-box li {
	margin-bottom: 5px;
}
</style>
