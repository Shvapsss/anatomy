<?php
/* @var $this GradeController */
/* @var $model Grade */


$this->breadcrumbs=array(
	'Оценки'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Оценки тестов', 'url'=>array('index')),
	array('label'=>'Добавить новую', 'url'=>array('create')),
    array('label'=>'Тесты', 'url'=>array('test/index')), 
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#grade-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление оценками тестов</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'grade-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'left',
		'right',
		'message',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
		),
	),
)); ?>