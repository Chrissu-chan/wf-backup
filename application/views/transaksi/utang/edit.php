<?= $this->form->model($model, null, 'enctype="multipart/form-data" id="frm-edit"') ?>
<?= $this->template->view('transaksi/utang/form') ?>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="update('<?= $model->id ?>')">{{update}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>