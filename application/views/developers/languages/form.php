<div id="frm-message"></div>
<div class="form-group">
    <label>{{localization}}</label>
    <?= $this->form->select('id_localization', lists($this->localizations_m->get(), 'id', 'country'), null, 'class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{type}}</label>
    <?= $this->form->text('type', null, 'id="type" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{key}}</label>
    <?= $this->form->text('key', null, 'id="key" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{message}}</label>
    <?= $this->form->text('message', null, 'id="message" class="form-control"') ?>
</div>