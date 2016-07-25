<li data-id="<?= $model->id ?>" class="<?= $model->active ? '' : 'inactive' ?>">
    <i class="fa fa-pencil"></i>
    <i class="fa fa-arrows-alt"></i>
    <?= $model->pro ? '<i class="fa fa-rub"></i>' : '' ?>
    <a class="title"><?= $model->title ?></a>
</li>