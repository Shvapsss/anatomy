<?php /* @var $this Controller */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <?php Yii::app()->bootstrap->register(); ?>
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/admin/css/main.css" rel="stylesheet">  
    </head>

    <body>
        <div class="container">
            <div class="form-signin">
                <?php echo $content; ?>
            </div>
        </div>
    </body>
    
</html>