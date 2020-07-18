<div id="frm-message"></div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{npwp}}</label>
    <?= $this->form->text('npwp', null, 'id="npwp" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{telepon}}</label>
    <?= $this->form->text('telepon', null, 'id="telepon" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{kota}}</label>
    <?= $this->form->select('id_kota', lists($this->kota_m->get(), 'id', 'kota', 'pilih_kota'), null, 'id="kota" class="form-control" data-input-type="select2" style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{alamat}}</label>
    <?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{note}}</label>
	<?= $this->form->textarea('keterangan', null, 'id="keterangan" class="form-control"') ?>
</div>