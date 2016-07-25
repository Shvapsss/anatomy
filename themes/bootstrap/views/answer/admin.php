<?php
/* @var $this AnswerController */
/* @var $model Answer */


$this->breadcrumbs=array(
	'Ответы'=>array('index','id'=>$model->quest_id),
	'Список',
);

$this->menu=array(
	array('label'=>'Список ответов', 'url'=>array('index','id'=>$model->quest_id)),
	array('label'=>'Создать ответ', 'url'=>array('create','id'=>$model->quest_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#answer-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список ответов к вопросу <?=$model->quest_id?> </h1>

<p>
    Вы можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
or <b>=</b>) в начале каждой поисковой строки.
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'answer-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'quest_id',
		'answer',
		'is_true',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
		),
	),
)); ?>