<?php
/* @var $this ChapterController */
/* @var $model Chapter */

$this->menu=array(
    array('label' => 'Управление главами', 'url' => array('index')),
    array('label' => 'Добавить главу', 'url' => array('create')),
);
?>

<h3>Изменить главу "<?php echo $model->title; ?>"</h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>