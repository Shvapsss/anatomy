<?php
/* @var $this QuestController */
/* @var $model Quest */


$this->breadcrumbs=array(
	'Вопросы'=>array('index','id'=>$model->test_id),
	'Список',
);

$this->menu=array(
	array('label'=>'Список вопросов', 'url'=>array('index', 'id'=>$model->test_id)),
	array('label'=>'Создать вопрос', 'url'=>array('create', 'id'=>$model->test_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#quest-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Список вопросов для теста <?=$model->test_id?></h1>

<p>
    Вы можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
или <b>=</b>) в начале каждой поисковой строки.
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'quest-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'question',
        'jpg',
        'pdf',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>