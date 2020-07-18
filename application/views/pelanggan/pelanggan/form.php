<div id="frm-message"></div>
<?= $this->form->hidden('id_cabang', $CI->session->userdata('cabang')->id, 'id="id_cabang"') ?>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_identitas}}</label>
    <?= $this->form->select('id_jenis_identitas', lists($this->jenis_identitas_m->get(), 'id', 'jenis_identitas'), null, 'id="id_jenis_identitas" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{no_identitas}}</label>
    <?= $this->form->text('no_identitas', null, 'id="no_identitas" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_kelamin}}</label>
    <?= $this->form->select('jenis_kelamin', $this->pelanggan_m->enum('jenis_kelamin'), null, 'id="jenis_kelamin" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{telepon}}</label>
    <?= $this->form->text('telepon', null, 'id="telepon" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{hp}}</label>
    <?= $this->form->text('hp', null, 'id="hp" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{kota}}</label>
    <?= $this->form->select('id_kota', lists($this->kota_m->get(), 'id', 'kota', 'pilih_kota'), null, 'id="id_kota" class="form-control" data-input-type="select2" style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{alamat}}</label>
    <?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
</div>