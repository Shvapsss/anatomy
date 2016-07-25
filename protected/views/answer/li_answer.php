<li data-id="<?= $model->id ?>" class="<?= $model->active ? '' : 'inactive' ?>">
    <i class="fa fa-pencil"></i>
    <label>
        <span class="title"><?= $model->title ?></span>
        <input type="radio" name="right" value="<?= $model->id ?>" />
        <i class="fa fa<?= $model->right ? '-dot' : '' ?>-circle-o"></i>
    </label>
</li>