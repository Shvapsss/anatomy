<?php
/* @var $this QuestController */
/* @var $model Quest */
?>

<?php
$this->breadcrumbs=array(
	'Вопросы'=>array('quest/index/'.$model->test_id),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список вопросов', 'url'=>array('index', 'id'=>$model->test_id)),
	array('label'=>'Список ответов', 'url'=>array('answer/index')),
);
?>

<h1>Добавить вопрос</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>