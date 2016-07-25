<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'user-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note">Поля, помеченные <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'email'); ?>

    <?php echo $form->labelEx($model,'firstname'); ?>
    <?php echo $form->textField($model,'firstname',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'firstname'); ?>
    
    <?php echo $form->labelEx($model,'passNew'); ?>
    <?php echo $form->passwordField($model,'passNew',array('size'=>20,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'passNew'); ?>

    <?php echo $form->labelEx($model,'passRepeat'); ?>
    <?php echo $form->passwordField($model,'passRepeat',array('size'=>20,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'passRepeat'); ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton('Сохранить', array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->