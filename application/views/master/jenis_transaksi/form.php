<div id="frm-message"></div>
<div class="form-group">
    <label>{{kode_jenis_transaksi}}</label>
    <?= $this->form->text('kode_jenis_transaksi', null, 'id="kode_jenis_transaksi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_transaksi}}</label>
    <?= $this->form->text('jenis_transaksi', null, 'id="jenis_transaksi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{tipe}}</label>
    <?= $this->form->select('tipe', $this->jenis_transaksi_m->enum('tipe'), null, 'id="tipe" class="form-control"') ?>
</div>