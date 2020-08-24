<div id="frm-message"></div>
<div class="form-group">
    <label>{{gudang}}</label>
    <?= $this->form->select('id_gudang', lists($this->cabang_gudang_m->view('cabang_gudang')->scope('utama')->scope('auth')->get(), 'id', 'gudang'), null, 'id="id_gudang" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{rak}}</label>
    <?= $this->form->text('rak', null, 'id="rak" class="form-control"') ?>
    <input type="text" style="display: none;">
</div>