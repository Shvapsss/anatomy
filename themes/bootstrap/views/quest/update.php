<?php
/* @var $this QuestController */
/* @var $model Quest */
?>

<?php
$this->breadcrumbs=array(
	'Вопросы'=>array('index','id'=>$model->test_id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список вопросов', 'url'=>array('index', 'id'=>$model->test_id)),
	array('label'=>'Создать вопрос', 'url'=>array('create', 'id'=>$model->test_id)),
	array('label'=>'Список ответов', 'url'=>array('answer/index', 'id'=>$model->id)),

);
?>

    <h1>Редактировать вопрос <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>