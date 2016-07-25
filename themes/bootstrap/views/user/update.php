<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    'Пользователи' => array('manage'),
    'Редактировать',
);

$this->menu=array(
	array('label' => 'Управление пользователями', 'url' => array('manage')),
	array('label' => 'Создать пользователя', 'url' => array('create')),
);
?>

<h3>Редактировать пользователя <?php echo $model->firstname; ?></h3>

<?php $this->renderPartial('_editForm', array('model' => $model)); ?>