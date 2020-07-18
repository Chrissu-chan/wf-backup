<?= $this->form->model($model, null, 'id="frm-edit"') ?>
<?= $this->template->view('supplier/kategori_supplier/form') ?>
    <div class="form-group">
        <label>{{induk}}</label>
        <?= $this->form->select('parent_id', tree_lists($this->kategori_supplier_m->get(), 'id', 'parent_id', 0, 'id', 'kategori_supplier', '- Induk -'), null, 'class="form-control"') ?>
    </div>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="update('<?= $model->id ?>')">{{update}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>