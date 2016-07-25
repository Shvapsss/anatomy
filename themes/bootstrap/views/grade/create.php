<?php
/* @var $this GradeController */
/* @var $model Grade */
?>

<?php
$this->breadcrumbs=array(
	'Оценки'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список оценок', 'url'=>array('index')),
);
?>

<h1>Добавить оценку</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>