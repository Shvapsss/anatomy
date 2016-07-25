<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="span3">
    <div class="well sidebar-nav">
        <?php
        $this->widget('bootstrap.widgets.TbNav', array(
            'type' => TbHtml::NAV_TYPE_LIST,
            'items' => array_merge(array(array('label' => 'Действия')), $this->menu),
        ));
        ?>
    </div><!--/.well -->
</div><!--/span-->

<div class="span8 well">
    <?php if (isset($this->breadcrumbs)): ?>
        <?php
        $this->widget('bootstrap.widgets.TbBreadcrumb', array(
            'links' => $this->breadcrumbs,
        ));
        ?><!-- breadcrumbs -->
    <?php endif ?>
    <?php echo $content; ?>
</div><!--/span-->

<?php $this->endContent(); ?>