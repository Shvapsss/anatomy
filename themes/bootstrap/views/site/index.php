<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<h2>Добро пожаловать в <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>

<div class="dashboard">
    <a href="/news/manage"><img src="/themes/bootstrap/assets/images/Pen.png"> <?php echo count(News::model()->findAll());?> Новостей</a>        
    <a href="/company/manage"><img src="/themes/bootstrap/assets/images/Laptop.png"> <?php echo count(Company::model()->findAll());?> Компаний</a>
    <a href="/category/manage"><img src="/themes/bootstrap/assets/images/Format_number.png"> <?php echo count(Category::model()->findAll());?> Категорий</a>
    <a href="/action/manage"><img src="/themes/bootstrap/assets/images/Credit_cards.png"> <?php echo count(Action::model()->findAll());?> Акций</a>
    <a href="/user/manage"><img src="/themes/bootstrap/assets/images/Users.png"> <?php echo count(User::model()->findAll());?> Пользователей</a>
</div>

















<?php 

?>