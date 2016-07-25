<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
    'Пользователи',
);

$this->menu=array(
    array('label'=>'Создать пользователя', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Управление пользователями</h3>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'user-grid',
        'type' => TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                'id' => array(
                    'name'   => 'id',
                    'filter' => false,
                ), 
                'email' => array(
                    'name'   => 'email',
                ), 
		'firstname',
                'registred' => array(
                    'name'   => 'registred',
                    'value'  => 'date("Y.m.j H:i", $data->registred)',
                    'filter' => false,
                ),
		array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update}{delete}',
		),
	),
)); ?>
