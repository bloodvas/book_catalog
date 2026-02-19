<?php
/* @var $this AuthorController */
/* @var $model Author */

$this->breadcrumbs=array(
	'Authors'=>array('index'),
	$model->full_name,
);

$this->menu=array(
	array('label'=>'List Author', 'url'=>array('index')),
	array('label'=>'Create Author', 'url'=>array('create'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Update Author', 'url'=>array('update', 'id'=>$model->id), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Delete Author', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Manage Author', 'url'=>array('admin'), 'visible'=>!Yii::app()->user->isGuest),
);
?>

<h1><?php echo $model->full_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'full_name',
		'created_at',
		'updated_at',
	),
)); ?>

<h3>Books by this author</h3>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$booksDataProvider,
	'itemView'=>'../book/_view',
)); ?>

<?php if (Yii::app()->user->isGuest): ?>
<h3>Subscribe to this author</h3>
<?php echo CHtml::link('Subscribe', array('subscription/subscribe', 'author_id'=>$model->id), array('class'=>'btn btn-primary')); ?>
<?php endif; ?>
