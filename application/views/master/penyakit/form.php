<div id="frm-message"></div>
<div class="form-group">
    <label>{{kode_penyakit}}</label>
    <?= $this->form->text('kode_penyakit', null, 'id="kode_penyakit" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_penyakit}}</label>
    <?= $this->form->select('id_jenis_penyakit', lists($this->jenis_penyakit_m->get(), 'id', 'jenis_penyakit'), null, 'id="id_jenis_penyakit" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{penyakit}}</label>
    <?= $this->form->text('penyakit', null, 'id="penyakit" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{keterangan}}</label>
    <?= $this->form->textarea('keterangan', null, 'id="keterangan" class="form-control"') ?>
</div>