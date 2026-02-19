<?php
/* @var $this SubscriptionController */
/* @var $model Subscription */

$this->breadcrumbs=array(
	'Подписки',
);

$this->menu=array(
	array('label'=>'Создать подписку', 'url'=>array('create')),
	array('label'=>'Управление подписками', 'url'=>array('admin')),
);
?>

<h1>Подписки</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'subscription-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
			'name'=>'author_id',
			'value'=>'$data->author ? $data->author->full_name : null',
			'filter'=>CHtml::listData(Author::model()->findAll(), 'id', 'full_name'),
		),
		'phone',
		'created_at',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
