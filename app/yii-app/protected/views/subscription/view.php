<?php
/* @var $this SubscriptionController */
/* @var $model Subscription */

$this->breadcrumbs=array(
	'Подписки'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список подписок', 'url'=>array('index')),
	array('label'=>'Создать подписку', 'url'=>array('create')),
	array('label'=>'Управление', 'url'=>array('admin')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array(
		'delete',
		'id'=>$model->id,
		'confirm'=>'Are you sure you want to delete this item?')),
	'csrf'=>true,
));
?>

<h1>Просмотр подписки #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name'=>'author_id',
			'value'=>$model->author ? $model->author->full_name : null,
		),
		'phone',
		'created_at',
	),
)); ?>
