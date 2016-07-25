<?php
/* @var $this ChapterController */
/* @var $model Chapter */


$this->breadcrumbs = array(
    'Главы',
);

$this->menu = array(
    array('label' => 'Добавить главу', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#chapter-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Управление главами</h3>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'chapter-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'rowHtmlOptionsExpression' => 'array("data-id"=>$data->id)',
    'columns' => array(
// 'id' => array('name' => 'id','filter' => false,),
        'title' => array(
            'name' => 'title',
            'cssClassExpression' => 'handle'
        ),
        'first_page',
        'upload_date' => array(
            'name' => 'upload_date',
            'value' => 'date("j F Y H:i:s", $data->upload_date)',
            //'value' => 'date("n.m.Y H:i", $data->upload_date)',
            'filter' => false,
        ),
        'paid' => array(
            'name' => 'paid',
            'value' => '(($data->paid == 1) ? "да": "нет")',
            'filter' => array(0 => "нет", 1 => "да"),
        ),
        'file',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
?>