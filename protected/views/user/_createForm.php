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

    <?php echo $form->labelEx($model,'pass'); ?>
    <?php echo $form->passwordField($model,'pass',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'pass'); ?>

    <?php echo $form->labelEx($model,'role'); ?>
    <?php echo $form->dropDownList($model,'role',  $model->roles); ?>
    <?php echo $form->error($model,'role'); ?>


    <div class="form-actions">
        <?php echo TbHtml::submitButton('Создать', array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->