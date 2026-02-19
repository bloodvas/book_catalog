<?php
/* @var $this SubscriptionController */
/* @var $model Subscription */

$this->breadcrumbs=array(
	'Subscriptions'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Subscriptions', 'url'=>array('admin')),
);
?>

<h1>Create Subscription</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
