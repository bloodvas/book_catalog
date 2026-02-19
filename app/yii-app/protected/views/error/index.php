<?php
/* @var $this ErrorController */

$this->breadcrumbs=array(
	'Ошибка',
);

$this->menu=array(
);
?>

<h1>Страница не найдена</h1>

<p>Извините, запрашиваемая страница не существует.</p>

<p>Возможные действия:</p>
<ul>
    <li><?php echo CHtml::link('Перейти на главную страницу', array('/site/index')); ?></li>
    <li><?php echo CHtml::link('Просмотреть книги', array('/book/index')); ?></li>
    <li><?php echo CHtml::link('Просмотреть авторов', array('/author/index')); ?></li>
    <?php if(Yii::app()->user->isGuest): ?>
        <li><?php echo CHtml::link('Войти в систему', array('/site/login')); ?></li>
    <?php else: ?>
        <li><?php echo CHtml::link('Управление книгами', array('/book/admin')); ?></li>
        <li><?php echo CHtml::link('Управление авторами', array('/author/admin')); ?></li>
    <?php endif; ?>
</ul>

<p>Если вы считаете, что это ошибка, пожалуйста, свяжитесь с администратором.</p>
