<?php
/* @var $this ReportController */

$this->breadcrumbs=array(
	'Reports',
);

?>

<h1>Отчеты</h1>

<div class="report-menu">
	<h2>Доступные отчеты:</h2>
	
	<ul class="nav nav-list">
		<li>
			<?php echo CHtml::link('ТОП-10 авторов по количеству книг', array('topAuthors')); ?>
			<p class="muted">Показывает 10 самых продуктивных авторов за выбранный год</p>
		</li>
		
		<!-- Future reports can be added here -->
		<li class="disabled">
			<a href="#">Статистика по жанрам</a>
			<p class="muted">Будет доступно в будущих версиях</p>
		</li>
		
		<li class="disabled">
			<a href="#">Динамика изданий</a>
			<p class="muted">Будет доступно в будущих версиях</p>
		</li>
	</ul>
</div>

<style>
.report-menu {
	margin-top: 20px;
}

.nav-list > li {
	margin-bottom: 15px;
}

.nav-list > li > a {
	font-weight: bold;
}

.nav-list > li.disabled > a {
	color: #999;
	cursor: not-allowed;
}

.nav-list .muted {
	font-size: 12px;
	margin-left: 20px;
	margin-top: 5px;
}
</style>
