    <?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Каталог книг</h1>

<p>Добро пожаловать в систему управления каталогом книг!</p>

<h2>Основные функции:</h2>

<?php if(Yii::app()->user->isGuest): ?>
    <h3>Для гостей:</h3>
    <ul>
        <li><?php echo CHtml::link('Просмотреть все книги', array('/book/index')); ?></li>
        <li><?php echo CHtml::link('Просмотреть всех авторов', array('/author/index')); ?></li>
        <li><?php echo CHtml::link('Подписаться на уведомления о новых книгах', array('/subscription/index')); ?></li>
        <li><?php echo CHtml::link('ТОП-10 авторов по количеству книг', array('/report/topAuthors')); ?></li>
    </ul>
    
    <h3>Для доступа к управлению:</h3>
    <p><?php echo CHtml::link('Войти в систему', array('/site/login'), array('class' => 'btn btn-primary')); ?></p>
<?php else: ?>
    <h3>Для авторизованных пользователей:</h3>
    <ul>
        <li><?php echo CHtml::link('Просмотреть все книги', array('/book/index')); ?></li>
        <li><?php echo CHtml::link('Просмотреть всех авторов', array('/author/index')); ?></li>
        <li><?php echo CHtml::link('Управление книгами', array('/book/admin')); ?></li>
        <li><?php echo CHtml::link('Управление авторами', array('/author/admin')); ?></li>
        <li><?php echo CHtml::link('Создать новую книгу', array('/book/create')); ?></li>
        <li><?php echo CHtml::link('Создать нового автора', array('/author/create')); ?></li>
        <li><?php echo CHtml::link('ТОП-10 авторов', array('/report/topAuthors')); ?></li>
    </ul>
    
    <p><?php echo CHtml::link('Выйти из системы', array('/site/logout'), array('class' => 'btn btn-secondary')); ?></p>
<?php endif; ?>

<h2>О системе:</h2>
<p>Каталог книг разработан на фреймворке Yii 1.0 с использованием базы данных MySQL.</p>
<p>Система поддерживает:</p>
<ul>
    <li>Управление книгами и авторами (CRUD операции)</li>
    <li>Множественных авторов для одной книги</li>
    <li>Подписку на уведомления о новых книгах</li>
    <li>SMS-уведомления через SMSpilot API</li>
    <li>Отчеты по популярности авторов</li>
    <li>Разграничение прав доступа (гость/пользователь)</li>
</ul>
