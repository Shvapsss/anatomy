<?php
/* @var $this ChapterController */
/* @var $model Chapter */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
    )); ?>

    <?php echo $form->textFieldControlGroup($model,'id',array('span'=>5)); ?>

    <?php echo $form->textFieldControlGroup($model,'title',array('span'=>5,'maxlength'=>255)); ?>

    <?php echo $form->textFieldControlGroup($model,'first_page',array('span'=>5)); ?>

    <?php echo $form->labelEx($model, 'paid'); ?>
    <?php echo $form->dropDownList($model,'paid', array(1 => 'да', 0 => 'нет'), array('empty' => '-')); ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton('Искать',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->