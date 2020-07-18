<div id="frm-message"></div>
<div class="form-group">
    <label>{{module}}</label>
    <?= $this->form->select('module_id', lists($this->modules_m->get(), 'id', 'module'), null, 'id="module_id" class="form-control"') ?>
</div>