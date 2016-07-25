<?php
/* @var $this QuestController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Quests',
);

$this->menu=array(
	array('label'=>'Create Quest','url'=>array('create')),
	array('label'=>'Manage Quest','url'=>array('admin')),
);
?>

<h1>Quests</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

















<?php 

?>