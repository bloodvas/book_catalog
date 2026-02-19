<?php
/* @var $this BookController */
/* @var $data Book */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isbn')); ?>:</b>
	<?php echo CHtml::encode($data->isbn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authors')); ?>:</b>
	<?php 
	$authors = array();
	foreach ($data->authors as $author) {
		$authors[] = CHtml::link(CHtml::encode($author->full_name), array('author/view', 'id'=>$author->id));
	}
	echo implode(', ', $authors);
	?>
	<br />

	<?php if ($data->cover_image): ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('cover_image')); ?>:</b>
	<br/>
	<?php echo CHtml::image(Yii::app()->baseUrl.'/images/covers/'.$data->cover_image, 'Cover', array('width'=>100)); ?>
	<br />
	<?php endif; ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

</div>
