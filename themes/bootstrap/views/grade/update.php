<?php
/* @var $this GradeController */
/* @var $model Grade */
?>

<?php
$this->breadcrumbs=array(
	'Оценки'=>array('index'),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список оценок', 'url'=>array('index')),
	array('label'=>'Добавить оценку', 'url'=>array('create')),
);
?>

    <h1>Редактировать оценки </h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>