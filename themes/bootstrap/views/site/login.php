<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';?>

<h2>Вход</h2>

<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'login-form',
            // 'class' => 'form-signin',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                    'validateOnSubmit'=>true,
            ),
    )); ?>
	
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email'); ?>
    <?php echo $form->error($model,'email'); ?>

    <?php echo $form->labelEx($model,'pass'); ?>
    <?php echo $form->passwordField($model,'pass'); ?>
    <?php echo $form->error($model,'pass'); ?>
    
    <label class="checkbox">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        Запомнить меня
        <?php echo $form->error($model,'rememberMe'); ?>
    </label>
    
    <?php echo TbHtml::submitButton('Login', array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
    )); ?>
    
<?php $this->endWidget(); ?>
</div><!-- form -->
