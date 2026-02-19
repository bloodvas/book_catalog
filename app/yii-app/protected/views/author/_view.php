<?php
/* @var $this AuthorController */
/* @var $data Author */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->full_name), array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />
	
	<?php if(Yii::app()->user->isGuest): ?>
	<br />
	<?php echo CHtml::link('Подписаться на SMS-уведомления', array('subscription/subscribe', 'author_id'=>$data->id), array('class'=>'btn btn-success btn-sm')); ?>
	<?php endif; ?>
</div>
