<?php
/* @var $this ReportController */
/* @var $model ReportForm */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Reports'=>array('index'),
	'TOP Authors',
);

$this->menu=array(
	array('label'=>'Export to CSV', 'url'=>array('exportTopAuthors'), 'linkOptions'=>array('target'=>'_blank')),
);
?>

<h1>ТОП-10 авторов по количеству книг</h1>

<div class="search-form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-form',
	'method'=>'post',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->label($model,'year_from'); ?>
		<?php echo $form->dropDownList($model,'year_from', $model->getYearOptions(), array('empty'=>'Выберите год')); ?>
		<?php echo $form->error($model,'year_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'year_to'); ?>
		<?php echo $form->dropDownList($model,'year_to', $model->getYearOptions(), array('empty'=>'Выберите год')); ?>
		<?php echo $form->error($model,'year_to'); ?>
	</div>

	<!-- Future expansion filters -->
	<div class="row">
		<?php echo $form->label($model,'genre'); ?>
		<?php echo $form->dropDownList($model,'genre', $model->getGenreOptions(), array('empty'=>'Все жанры')); ?>
		<?php echo $form->error($model,'genre'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'author_id'); ?>
		<?php echo $form->dropDownList($model,'author_id', $model->getAuthorOptions(), array('empty'=>'Все авторы')); ?>
		<?php echo $form->error($model,'author_id'); ?>
	</div>

	<!-- Future expansion filters -->
	<div class="row">
		<?php echo $form->label($model,'min_books'); ?>
		<?php echo $form->textField($model,'min_books'); ?>
		<?php echo $form->error($model,'min_books'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_books'); ?>
		<?php echo $form->textField($model,'max_books'); ?>
		<?php echo $form->error($model,'max_books'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Показать', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->

<?php if ($dataProvider !== null): ?>
<div class="report-results">
	<h2>Результаты за <?php echo CHtml::encode($model->year_from . ' - ' . $model->year_to); ?></h2>
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'top-authors-grid',
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			'full_name' => array(
				'name' => 'full_name',
				'header' => 'Автор',
				'type' => 'raw',
				'value' => 'CHtml::link(CHtml::encode($data->full_name), array("author/view", "id"=>$data->id))',
			),
			'book_count' => array(
				'header' => 'Количество книг',
				'type' => 'html',
				'value' => '"<span class=\\"badge\\">" . $data->book_count . "</span>"',
			),
			array(
				'header' => 'Действия',
				'type' => 'raw',
				'value' => 'CHtml::link("Просмотреть книги", array("book/index", "Book[author_ids]"=>$data->id), array("class"=>"btn btn-small"))',
			),
		),
		'emptyText' => 'За указанный год книги не найдены',
		'summaryText' => 'Показано {start}-{end} из {count} авторов',
	)); ?>
	
	<div class="export-section">
		<form method="post" action="<?php echo Yii::app()->createUrl('report/exportTopAuthors'); ?>" target="_blank" style="display: inline;">
			<input type="hidden" name="ReportForm[year_from]" value="<?php echo CHtml::encode($model->year_from); ?>" />
			<input type="hidden" name="ReportForm[year_to]" value="<?php echo CHtml::encode($model->year_to); ?>" />
			<input type="hidden" name="ReportForm[genre]" value="<?php echo CHtml::encode($model->genre); ?>" />
			<input type="hidden" name="ReportForm[author_id]" value="<?php echo CHtml::encode($model->author_id); ?>" />
			<input type="hidden" name="ReportForm[min_books]" value="<?php echo CHtml::encode($model->min_books); ?>" />
			<input type="hidden" name="ReportForm[max_books]" value="<?php echo CHtml::encode($model->max_books); ?>" />
			<button type="submit" class="btn btn-success">Экспортировать в CSV</button>
		</form>
	</div>
</div>
<?php endif; ?>

<style>
.badge {
	background-color: #468847;
	color: white;
	padding: 2px 6px;
	border-radius: 3px;
	font-weight: bold;
}

.search-form {
	background-color: #f9f9f9;
	padding: 15px;
	margin-bottom: 20px;
	border-radius: 5px;
}

.search-form .row {
	margin-bottom: 10px;
}

.report-results {
	margin-top: 20px;
}

.export-section {
	margin-top: 15px;
	text-align: right;
}
</style>
