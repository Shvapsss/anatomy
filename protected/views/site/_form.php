<?php
/* @var $this ChapterController */
/* @var $model Chapter */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'chapter-form',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <p class="help-block">Поля, помеченные <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5,'maxlength' => 255)); ?>

    <?php echo $form->textFieldControlGroup($model, 'first_page', array('span' => 5)); ?>
    
    <?php echo $form->labelEx($model, 'paid'); ?>
    <?php echo $form->dropDownList($model, 'paid', array(1 => 'да', 0 => 'нет')); ?>
    <?php echo $form->error($model, 'paid'); ?>
    
    <?php echo $form->labelEx($model, 'file'); ?>
    <?php echo $form->fileField($model, 'file'); ?>
    <?php echo $form->error($model, 'file'); ?>

    <?php if($model->file): ?> 
    <a target="_blank" href="<?php echo '/files/pdf/'.$model->file; ?>"><?php echo $model->file; ?></a>
    <?php endif; ?>
   
    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->