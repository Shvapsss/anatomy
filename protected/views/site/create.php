<?php
/* @var $this ChapterController */
/* @var $model Chapter */
?>

<?php
$this->breadcrumbs = array(
    'Главы' => array('index'),
    'Добавить',
);

$this->menu=array(
    array('label'=>'Управление главами', 'url' => array('index')),
);
?>

<h3>Добавить главу</h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>