<?php
/* @var $this QuestController */
/* @var $model Quest */
?>

<?php
$this->breadcrumbs=array(
	'Quests'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Quest', 'url'=>array('index')),
	array('label'=>'Create Quest', 'url'=>array('create')),
	array('label'=>'View Quest', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Quest', 'url'=>array('admin')),
);
?>

    <h1>Update Quest <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>