<div class="form-group">
    <input type="text" value="<?= isset($result) ? $result->till : '' ?>" class="form-control min" name="till[]" placeholder="от .. %">
    <input type="text" value="<?= isset($result) ? $result->from : '' ?>" class="form-control min" name="from[]" placeholder="до .. %">
    <input type="text" value="<?= isset($result) ? $result->text : '' ?>" class="form-control" name="text[]" placeholder="Сообщение">
</div>
