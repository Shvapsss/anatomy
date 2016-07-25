<?php
/* @var $this TestController */
/* @var $model Test */
?>

<?php
$this->breadcrumbs=array(
	'Тесты'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список тестов', 'url'=>array('index')),
);
?>

<h1>Добавить тест</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>