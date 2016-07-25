<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->bootstrap->register(); ?>
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/main.css" rel="stylesheet" />
    </head>

    <body>

        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'brandLabel' => 'Onecard Dashboard',
            'brandUrl' => '/',
            'color' => TbHtml::NAVBAR_COLOR_INVERSE,
            'display' => 'fixed-top',
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbNav',
                    'activateItems' => true,
                    'activateParents' => true,
                    'items' => array(
                        array('label' => 'Новости', 'url' => array('/news/manage')),
                        array('label' => 'Компании', 'url' => array('/company/manage')),
                        array('label' => 'Категории', 'url' => array('/category/manage')),
                        array('label' => 'Акции', 'url' => array('/action/manage')),
                        array('label' => 'Пользователи', 'url' => array('/user/manage')),
                        array('label' => 'Выйти (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                    ),
                ),
            ),
        ));
        ?>

        <div class="container">
            <div class="row">
                <?php echo $content; ?>
            </div><!--/row-->
            <footer>
                <div class="text-center">
                    <p>Created by <img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/images/vexadev_logo.png"/></p>
                    <?php echo Yii::powered(); ?>
                </div>
            </footer>

        </div><!--/.fluid-container-->
    </body>
</html>
