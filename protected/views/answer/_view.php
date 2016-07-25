<?php
/* @var $this AnswerController */
/* @var $data Answer */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quest_id')); ?>:</b>
	<?php echo CHtml::encode($data->quest_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('answer')); ?>:</b>
	<?php echo CHtml::encode($data->answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_true')); ?>:</b>
	<?php echo CHtml::encode($data->is_true); ?>
	<br />


</div>