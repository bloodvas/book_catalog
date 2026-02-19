<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Update Book', 'url'=>array('update', 'id'=>$model->id), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Delete Book', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Manage Book', 'url'=>array('admin'), 'visible'=>!Yii::app()->user->isGuest),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'title',
		'year',
		'isbn',
		'description',
		array(
			'name'=>'cover_image',
			'type'=>'raw',
			'value'=>$model->cover_image ? CHtml::image(Yii::app()->baseUrl.'/images/covers/'.$model->cover_image, 'Cover', array('width'=>200)) : 'No image',
		),
		array(
			'name'=>'authors',
			'type'=>'raw',
			'value'=>function($data) {
				$authors = array();
				foreach ($data->authors as $author) {
					$authors[] = CHtml::link(CHtml::encode($author->full_name), array('author/view', 'id'=>$author->id));
				}
				return implode(', ', $authors);
			},
		),
		'created_at',
		'updated_at',
	),
)); ?>
