<div id="frm-message"></div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{diskon}}</label>
            <div class="input-group">
                <?= $this->form->number('diskon', null, 'id="diskon" class="form-control text-right" data-input-type="number-format"', "") ?>
                <span class="input-group-addon">%</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{potongan}}</label>
            <?= $this->form->number('potongan', null, 'id="potongan" class="form-control text-right" data-input-type="number-format"', "") ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{cabang}}</label>
    <?= $this->form->select('diskon_cabang[]', lists($this->cabang_m->scope('auth')->get(), 'id', 'nama'), null, 'id="diskon_cabang" class="form-control" data-input-type="select2" multiple') ?>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{tanggal_mulai}}</label>
            <div class="input-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->date('tanggal_mulai', date('d-m-Y'), 'id="tanggal_mulai" class="form-control" data-input-type="datepicker"') ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{tanggal_selesai}}</label>
            <div class="input-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->date('tanggal_selesai', null, 'id="tanggal_selesai" class="form-control" data-input-type="datepicker"') ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{keterangan}}</label>
    <?= $this->form->textarea('keterangan', null, 'id="keterangan" class="form-control"') ?>
</div>
<hr>
<h4>{{kriteria}}</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('diskon_kondisi[kode_produk[column]]', 1, (isset($this->form->data('diskon_kondisi')['kode_produk'])) ? true : false, 'id="diskon_kondisi-kode_produk-column" onchange=checked_attribute(\'kode_produk\')') ?> {{kode_produk}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[kode_produk][operation]', array('=' => '='), NULL, 'id="diskon_kondisi-kode_produk-operation" class="form-control" '.(isset($this->form->data('diskon_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->text('diskon_kondisi[kode_produk][from]', NULL, 'id="diskon_kondisi-kode_produk-from" class="form-control" '.(isset($this->form->data('diskon_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('diskon_kondisi[kode_produk][to]', NULL, 'id="diskon_kondisi-kode_produk-to" class="form-control" '.(isset($this->form->data('diskon_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('diskon_kondisi[jenis_produk][column]', 1, (isset($this->form->data('diskon_kondisi')['jenis_produk'])) ? true : false, 'id="diskon_kondisi-jenis_produk-column" onchange=checked_attribute(\'jenis_produk\')') ?> {{jenis_produk}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[jenis_produk][operation]', array('=' => '='), NULL, 'id="diskon_kondisi-jenis_produk-operation" class="form-control" '.(isset($this->form->data('diskon_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[jenis_produk][from][]', array(
                'barang' => $this->localization->lang('barang'),
                'jasa' => $this->localization->lang('jasa'),
                'paket' => $this->localization->lang('paket')
            ), NULL, 'id="diskon_kondisi-jenis_produk-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('diskon_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
        </div>
        <?= $this->form->hidden('diskon_kondisi[jenis_produk][to]', NULL, 'id="diskon_kondisi-jenis_produk-to" class="form-control" '.(isset($this->form->data('diskon_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('diskon_kondisi[kategori][column]', 1, (isset($this->form->data('diskon_kondisi')['kategori'])) ? true : false, 'id="diskon_kondisi-kategori-column" onchange=checked_attribute(\'kategori\')') ?> {{kategori}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[kategori][operation]', array('=' => '='), NULL, 'id="diskon_kondisi-kategori-operation" class="form-control" '.(isset($this->form->data('diskon_kondisi')['kategori']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[kategori][from][]', lists($this->view_kategori_m->get(), 'id', function($row) {
                return $row->id.' - '.$row->kategori;
            }), NULL, 'id="diskon_kondisi-kategori-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('diskon_kondisi')['kategori']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('diskon_kondisi[kategori][to]', NULL, 'id="diskon_kondisi-kategori-to" class="form-control" '.(isset($this->form->data('diskon_kondisi')['kategori']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('diskon_kondisi[jenis][column]', 1, (isset($this->form->data('diskon_kondisi')['jenis'])) ? true : false, 'id="diskon_kondisi-jenis-column" onchange=checked_attribute(\'jenis\')') ?> {{jenis}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[jenis][operation]', array('=' => '='), NULL, 'id="diskon_kondisi-jenis-operation" class="form-control" '.(isset($this->form->data('diskon_kondisi')['jenis']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[jenis][from][]', lists($this->jenis_obat_m->get(), 'id', 'jenis_obat'), NULL, 'id="diskon_kondisi-jenis-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('diskon_kondisi')['jenis']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('diskon_kondisi[jenis][to]', NULL, 'id="diskon_kondisi-jenis-to" class="form-control" '.(isset($this->form->data('diskon_kondisi')['jenis']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('diskon_kondisi[harga][column]', 1, (isset($this->form->data('diskon_kondisi')['harga'])) ? true : false, 'id="diskon_kondisi-harga-column" onchange=checked_attribute(\'harga\')') ?> {{harga}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('diskon_kondisi[harga][operation]', array('=' => '=', '<=' => '<=', '>=' => '>='), NULL, 'id="diskon_kondisi-harga-operation" class="form-control" '.(isset($this->form->data('diskon_kondisi')['harga']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->number('diskon_kondisi[harga][from]', NULL, 'id="diskon_kondisi-harga-from" class="form-control text-right" data-input-type="number-format" '.(isset($this->form->data('diskon_kondisi')['harga']) ? '' : 'disabled').'', '') ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->number('diskon_kondisi[harga][to]', NULL, 'id="diskon_kondisi-harga-to" class="form-control text-right" data-input-type="number-format" '.(isset($this->form->data('diskon_kondisi')['harga']) ? '' : 'disabled').'', '') ?>
        </div>
    </div>
</div>