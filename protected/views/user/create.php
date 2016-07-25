<?php
/* @var $this UserController */
/* @var $model User */

$this->menu = array(
    array('label' => 'Управление пользователями', 'url' => array('manage')),
);
?>

<h3>Создать пользователя</h3>

<?php $this->renderPartial('_createForm', array('model' => $model)); ?>