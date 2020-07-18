<?= $this->form->model($model, null, 'id="frm-reset_password"') ?>
<div id="frm-message"></div>
<div class="form-group">
    <label>{{username}}</label>
    <?= $this->form->text('username', null, 'id="username" class="form-control" readonly') ?>
</div>
<div class="form-group">
    <label>{{password}}</label>
    <?= $this->form->password('password', null, 'id="password" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{confirm_password}}</label>
    <?= $this->form->password('confirm_password', null, 'id="confirm_password" class="form-control"') ?>
</div>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="resetPasswordStore('<?= $model->id ?>')">{{reset_password}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>