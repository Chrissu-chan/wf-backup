<div id="frm-message"></div>
<div class="form-group">
    <label>{{kode_member}}</label>
    <?= $this->form->text('kode', 'MBR201808001', 'id="kode" class="form-control" disabled') ?>
</div>
<div class="form-group">
    <label>{{data_jenis_member}}</label>
    <?= $this->form->select('id_jenis_member', array('VVIP','VIP','Reguler'), null, 'id="id_jenis_member" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{nama_pelanggan}}</label>
    <?= $this->form->text('id_pelanggan', null, 'id="id_pelanggan" class="form-control"') ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{jenis_identitas}}</label>
            <?= $this->form->select('id_jenis_identitas', array('KTP','SIM','KK'), null, 'id="id_jenis_identitas" class="form-control"') ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{no_identitas}}</label>
            <?= $this->form->text('no_identitas', null, 'id="no_identitas" class="form-control"') ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{jenis_kelamin}}</label>
    <?= $this->form->select('jenis_kelamin', array('Laki-laki','Perempuan'), null, 'id="jenis_kelamin" class="form-control"') ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{telepon}}</label>
            <?= $this->form->text('telepon', null, 'id="telepon" class="form-control"') ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{hp}}</label>
            <?= $this->form->text('hp', null, 'id="hp" class="form-control"') ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{alamat}}</label>
    <?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{tanggal_daftar}}</label>
    <div class="input-group date">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <?= $this->form->text('tanggal_daftar', date('d-m-Y'), 'id="tanggal_daftar" class="form-control" disabled') ?>
    </div>
</div>
<div class="form-group">
    <label>{{tanggal_expired}}</label>
    <div class="input-group date">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <?= $this->form->text('tanggal_expired', date('d-m-Y', strtotime('+150 days')), 'id="tanggal_expired" class="form-control" disabled') ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{harga}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('harga', null, 'id="harga" class="form-control text-right"') ?>
            </div>
        </div>
        <div class="form-group">
            <label>{{diskon}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('diskon', null, 'id="diskon" class="form-control text-right"') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{potongan}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('potongan', null, 'id="potongan" class="form-control text-right"') ?>
            </div>
        </div>
        <div class="form-group">
            <label>{{total}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('total', null, 'id="total" class="form-control text-right" disabled') ?>
            </div>
        </div>
    </div>
</div>