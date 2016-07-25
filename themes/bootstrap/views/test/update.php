<?php
/* @var $this TestController */
/* @var $model Test */
?>

<?php
$this->breadcrumbs=array(
	'Тесты'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список тестов', 'url'=>array('index', 'id'=>$model->id)),
	array('label'=>'Добавить тест', 'url'=>array('create')),
	array('label'=>'Вопросы к тесту', 'url'=>array('quest/index', 'id'=>$model->id)),

);
?>

    <h1>Редактировать тест <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>