<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'answer-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
)); ?>

    <p class="help-block">Поля, отмченные <span class="required">*</span> обязательны.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'quest_id',array('disabled'=>true,'span'=>5)); ?>

            <?php echo $form->textAreaControlGroup($model,'answer',array('rows'=>6,'span'=>8)); ?>

            <?php echo $form->checkBoxControlGroup($model,'is_true', $htmlOptions = array()); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->