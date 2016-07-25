<?php
/* @var $this QuestController */
/* @var $model Quest */
?>

<?php
$this->breadcrumbs=array(
	'Quests'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Quest', 'url'=>array('index')),
	array('label'=>'Create Quest', 'url'=>array('create')),
	array('label'=>'Update Quest', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quest', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Quest', 'url'=>array('admin')),
);
?>

<h1>View Quest #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'test_id',
		'question',
	),
)); ?>