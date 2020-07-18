<div id="frm-message"></div>
<div class="form-group">
    <label>{{data_pelanggan}}</label>
    <?= $this->form->text('id_pelanggan', null, 'id="id_pelanggan" class="form-control"') ?>
    <div class="attached-document">
        <table class="table table-bordered table-condensed">
            <tr>
                <th>{{nama}}</th>
                <th>{{jenis_identitas}}</th>
                <th>{{hp}}</th>
                <th>{{jenis_kelamin}}</th>
                <th>{{alamat}}</th>
            </tr>
            <tr>
                <td>Sizka LH</td>
                <td>KTP</td>
                <td>Perempuan</td>
                <td>087888999000</td>
                <td>Podorejo, Sumbergempol, Tulungagung</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{kode_member}}</label>
            <?= $this->form->text('kode', 'MBR201808001', 'id="kode" class="form-control" disabled') ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{data_jenis_member}}</label>
            <?= $this->form->text('id_jenis_member', 'VVIP', 'id="id_jenis_member" class="form-control" disabled') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{tanggal_daftar}}</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->text('tanggal_daftar', date('d-m-Y'), 'id="tanggal_daftar" class="form-control" disabled') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{tanggal_expired}}</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->text('tanggal_expired', date('d-m-Y', strtotime('+150 days')), 'id="tanggal_expired" class="form-control" disabled') ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{jenis_pembaruan_member}}</label>
            <?= $this->form->select('id_jenis_member', array('VVIP','VIP','Reguler'), null, 'id="id_jenis_member" class="form-control"') ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{tanggal_perpanjangan}}</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->text('tanggal_expired', date('d-m-Y', strtotime('+300 days')), 'id="tanggal_expired" class="form-control" disabled') ?>
            </div>
        </div>
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
                <span class="input-group-addon">%</span>
                <?= $this->form->text('diskon', null, 'id="diskon" class="form-control text-right"') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{ppn}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('ppn', null, 'id="ppn" class="form-control text-right"') ?>
            </div>
        </div>
        <div class="form-group">
            <label>{{convert_diskon}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('convert_diskon', null, 'id="convert_diskon" class="form-control text-right" disabled') ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{total}}</label>
    <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <?= $this->form->text('total', null, 'id="total" class="form-control text-right" disabled') ?>
    </div>
</div>
<div class="form-group">
    <label>{{cara_bayar}}</label>
    <?= $this->form->select('cara_bayar', array('Kas','Bank'), null, 'id="cara_bayar" class="form-control"') ?>
</div>
<!--If Bayar Kas-->
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{bayar}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('bayar', null, 'id="bayar" class="form-control text-right"') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{kembali}}</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <?= $this->form->text('kembali', null, 'id="kembali" class="form-control text-right" disabled') ?>
            </div>
        </div>
    </div>
</div>
<!--If Bayar Bank-->
<div class="form-group">
    <label>{{no_referensi_pembayaran}}</label>
    <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <?= $this->form->text('no_referensi_pembayaran', null, 'id="no_referensi_pembayaran" class="form-control text-right"') ?>
    </div>
</div>