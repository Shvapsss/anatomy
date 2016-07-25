<?php
/* @var $this AnswerController */
/* @var $model Answer */
?>

<?php
$this->breadcrumbs=array(
	'Ответы'=>array('index','id'=>$model->quest_id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список ответов', 'url'=>array('index','id'=>$model->quest_id)),
	array('label'=>'Создать ответ', 'url'=>array('create', 'id'=>$model->quest_id)),
);
?>

    <h1>Редактировать ответ <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>