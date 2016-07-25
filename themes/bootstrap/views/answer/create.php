<?php
/* @var $this AnswerController */
/* @var $model Answer */
?>

<?php
$this->breadcrumbs=array(
	'Ответы'=>array('index','id'=>$model->quest_id),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список ответов', 'url'=>array('index','id'=>$model->quest_id)),
);
?>

<h1>Добавить ответ</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>