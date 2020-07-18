<?= $this->form->model($model, null, 'id="frm-edit"') ?>
<?= $this->template->view('master/satuan/form') ?>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="update('<?= $model->id ?>')">{{update}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>