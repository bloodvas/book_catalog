<?php
/* @var $this SmsController */
/* @var $balance array */

$this->breadcrumbs=array(
	'SMS'=>array('index'),
	'Balance',
);

$this->menu=array(
	array('label'=>'Test SMS', 'url'=>array('test')),
);
?>

<h1>Баланс SMS сервиса</h1>

<div class="balance-info">
	<?php if (isset($balance['error']) && $balance['error']): ?>
		<div class="flash-error">
			Ошибка получения баланса: <?php echo CHtml::encode($balance['message']); ?>
		</div>
	<?php else: ?>
		<div class="balance-display">
			<h3>Текущий баланс: <span class="balance-amount"><?php echo isset($balance['balance']) ? $balance['balance'] : 'N/A'; ?></span> SMS</h3>
		</div>
		
		<?php if (isset($balance['cost'])): ?>
			<p>Стоимость одного SMS: <?php echo $balance['cost']; ?></p>
		<?php endif; ?>
		
		<?php if (isset($balance['currency'])): ?>
			<p>Валюта: <?php echo $balance['currency']; ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>

<div class="api-response">
	<h4>Полный ответ API:</h4>
	<pre><?php echo CHtml::encode(print_r($balance, true)); ?></pre>
</div>

<style>
.balance-info {
	margin-bottom: 20px;
}

.balance-display {
	background-color: #dff0d8;
	border: 1px solid #d6e9c6;
	padding: 20px;
	border-radius: 5px;
	text-align: center;
}

.balance-amount {
	font-size: 24px;
	font-weight: bold;
	color: #468847;
}

.api-response {
	margin-top: 20px;
}

.api-response pre {
	background-color: #f5f5f5;
	padding: 15px;
	border-radius: 5px;
	overflow-x: auto;
}
</style>
