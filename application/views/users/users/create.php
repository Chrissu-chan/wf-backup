<?= $this->form->open(null, 'id="frm-create"') ?>
<div id="frm-message"></div>
<div class="form-group">
    <label>{{username}}</label>
    <?= $this->form->text('username', null, 'id="username" class="form-control"') ?>
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
    <label>{{name}}</label>
    <?= $this->form->text('name', null, 'id="name" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{device_id}}</label>
	<?= $this->form->text('device_id', null, 'id="device_id" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{cabang}}</label>
    <?= $this->form->select('cabang[]', lists($this->cabang_m->get(), 'id', 'nama'), null, 'id="cabang" class="form-control" data-input-type="select2" data-placeholder="'.$this->localization->lang('semua_cabang').'" style="width:100%;" multiple') ?>
</div>
<div class="form-group">
    <label>{{roles}}</label>
    <?= $this->form->select('roles[]', lists($this->roles_m->get(), 'id', 'role'), null, 'id="roles" class="form-control" data-input-type="select2" style="width:100%;" multiple') ?>
</div>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="store()">{{store}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>

<script>
    $(function() {
        $('#frm-create').buildForm();
    });
</script>