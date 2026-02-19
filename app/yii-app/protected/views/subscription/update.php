<?php
/* @var $this SubscriptionController */
/* @var $model Subscription */

$this->breadcrumbs=array(
	'Подписки'=>array('index'),
	$model->id=>array('view', 'id'=>$model->id),
);

$this->menu=array(
	array('label'=>'Список подписок', 'url'=>array('index')),
	array('label'=>'Создать подписку', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление', 'url'=>array('admin')),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array(
		'delete',
		'id'=>$model->id,
		'confirm'=>'Are you sure you want to delete this item?')),
	'csrf'=>true,
));
?>

<h1>Изменить подписку #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
