<div id="frm-message"></div>
<div class="form-group">
    <label>{{kode}}</label>
    <?= $this->form->text('kode', null, 'id="kode" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{barcode}}</label>
    <?= $this->form->text('barcode', null, 'id="barcode" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{kategori_barang}}</label>
    <?= $this->form->select('id_kategori_barang', lists($this->kategori_barang_m->get(), 'id', 'kategori_barang'), null, 'class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_barang}}</label>
    <?= $this->form->select('id_jenis_barang', lists($this->jenis_barang_m->get(), 'id', 'jenis_barang'), null, 'id="id_jenis_barang" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{satuan_barang}}</label>
    <?= $this->form->select('id_satuan_barang', lists($this->satuan_barang_m->scope('parent')->get(), 'id', function($model) {
        return $model->kode .' - '. $model->satuan_barang;
    }), null, 'id="id_satuan_barang" class="form-control"') ?>
</div>