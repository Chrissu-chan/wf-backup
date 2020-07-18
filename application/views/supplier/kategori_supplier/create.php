<?= $this->form->open(null, 'id="frm-create"') ?>
<?= $this->template->view('supplier/kategori_supplier/form') ?>
<?= $this->form->hidden('parent_id', $parent_id, 'id="parent_id"') ?>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="store()">{{store}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>