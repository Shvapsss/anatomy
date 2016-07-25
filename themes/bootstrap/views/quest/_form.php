<?php
/* @var $this QuestController */
/* @var $model Quest */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'quest-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

    <p class="help-block">Поля, отмеченные <span class="required">*</span> обязятельны.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'test_id',array('disabled'=>true,'span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'question',array('span'=>5)); ?>

            <?php echo $form->labelEx($model, 'jpg'); ?>
            <?php echo $form->fileField($model, 'jpg'); ?>
            <?php echo $form->error($model, 'jpg'); ?>

            <?php if($model->jpg): ?>
            <a target="_blank" href="<?php echo '/files/pdf/'.$model->jpg; ?>"><?php echo $model->jpg; ?></a>
            <?php endif; ?>

            <?php echo $form->labelEx($model, 'pdf'); ?>
            <?php echo $form->fileField($model, 'pdf'); ?>
            <?php echo $form->error($model, 'pdf'); ?>

            <?php if($model->pdf): ?>
            <a target="_blank" href="<?php echo '/files/pdf/'.$model->pdf; ?>"><?php echo $model->pdf; ?></a>
            <?php endif; ?>
        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->