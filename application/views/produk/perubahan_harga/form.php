<div id="frm-message"></div>
<div class="form-group">
    <?= $this->form->checkbox('permanen', 1, FALSE, 'id="permanen" '.$this->form->disabled(array('edit'))) ?><label>{{permanen}}</label>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{perubahan_harga}}</label>
            <div class="input-group input-group">
                <?= $this->form->number('perubahan_harga', null, 'id="perubahan_harga" class="form-control text-right" data-input-type="number-format"', "") ?>
                <span class="input-group-addon">%</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{cabang}}</label>
            <?= $this->form->select('perubahan_harga_cabang[]', lists($this->cabang_m->scope('auth')->get(), 'id', 'nama'), null, 'id="perubahan_harga_cabang" class="form-control" data-input-type="select2" multiple') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{tanggal_mulai}}</label>
            <div class="input-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->date('tanggal_mulai', date('d-m-Y'), 'id="tanggal_mulai" class="form-control"') ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{tanggal_selesai}}</label>
            <div class="input-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->date('tanggal_selesai', null, 'id="tanggal_selesai" class="form-control"') ?>
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
            <?= $this->form->checkbox('perubahan_harga_kondisi[kode_produk[column]]', 1, (isset($this->form->data('perubahan_harga_kondisi')['kode_produk'])) ? true : false, 'id="perubahan_harga_kondisi-kode_produk-column" onchange=checked_attribute(\'kode_produk\')') ?> {{kode_produk}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[kode_produk][operation]', array('=' => '='), NULL, 'id="perubahan_harga_kondisi-kode_produk-operation" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->text('perubahan_harga_kondisi[kode_produk][from]', NULL, 'id="perubahan_harga_kondisi-kode_produk-from" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('perubahan_harga_kondisi[kode_produk][to]', NULL, 'id="perubahan_harga_kondisi-kode_produk-to" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['kode_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('perubahan_harga_kondisi[jenis_produk][column]', 1, (isset($this->form->data('perubahan_harga_kondisi')['jenis_produk'])) ? true : false, 'id="perubahan_harga_kondisi-jenis_produk-column" onchange=checked_attribute(\'jenis_produk\')') ?> {{jenis_produk}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[jenis_produk][operation]', array('=' => '='), NULL, 'id="perubahan_harga_kondisi-jenis_produk-operation" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[jenis_produk][from][]', array(
                'barang' => $this->localization->lang('barang'),
                'jasa' => $this->localization->lang('jasa'),
                'paket' => $this->localization->lang('paket')
            ), NULL, 'id="perubahan_harga_kondisi-jenis_produk-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('perubahan_harga_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
        </div>
        <?= $this->form->hidden('perubahan_harga_kondisi[jenis_produk][to]', NULL, 'id="perubahan_harga_kondisi-jenis_produk-to" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['jenis_produk']) ? '' : 'disabled').'') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('perubahan_harga_kondisi[kategori][column]', 1, (isset($this->form->data('perubahan_harga_kondisi')['kategori'])) ? true : false, 'id="perubahan_harga_kondisi-kategori-column" onchange=checked_attribute(\'kategori\')') ?> {{kategori}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[kategori][operation]', array('=' => '='), NULL, 'id="perubahan_harga_kondisi-kategori-operation" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['kategori']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[kategori][from][]', lists($this->view_kategori_m->get(), 'id', function($row) {
                return $row->id.' - '.$row->kategori;
            }), NULL, 'id="perubahan_harga_kondisi-kategori-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('perubahan_harga_kondisi')['kategori']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('perubahan_harga_kondisi[kategori][to]', NULL, 'id="perubahan_harga_kondisi-kategori-to" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['kategori']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('perubahan_harga_kondisi[jenis][column]', 1, (isset($this->form->data('perubahan_harga_kondisi')['jenis'])) ? true : false, 'id="perubahan_harga_kondisi-jenis-column" onchange=checked_attribute(\'jenis\')') ?> {{jenis}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[jenis][operation]', array('=' => '='), NULL, 'id="perubahan_harga_kondisi-jenis-operation" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['jenis']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[jenis][from][]', lists($this->jenis_obat_m->get(), 'id', 'jenis_obat'), NULL, 'id="perubahan_harga_kondisi-jenis-from" class="form-control" data-input-type="select2" multiple '.(isset($this->form->data('perubahan_harga_kondisi')['jenis']) ? '' : 'disabled').'') ?>
            <?= $this->form->hidden('perubahan_harga_kondisi[jenis][to]', NULL, 'id="perubahan_harga_kondisi-jenis-to" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['jenis']) ? '' : 'disabled').'') ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->checkbox('perubahan_harga_kondisi[harga][column]', 1, (isset($this->form->data('perubahan_harga_kondisi')['harga'])) ? true : false, 'id="perubahan_harga_kondisi-harga-column" onchange=checked_attribute(\'harga\')') ?> {{harga}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->select('perubahan_harga_kondisi[harga][operation]', array('=' => '=', '<=' => '<=', '>=' => '>='), NULL, 'id="perubahan_harga_kondisi-harga-operation" class="form-control" '.(isset($this->form->data('perubahan_harga_kondisi')['harga']) ? '' : 'disabled').'') ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->number('perubahan_harga_kondisi[harga][from]', NULL, 'id="perubahan_harga_kondisi-harga-from" class="form-control text-right" data-input-type="number-format" '.(isset($this->form->data('perubahan_harga_kondisi')['harga']) ? '' : 'disabled').'', '') ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= $this->form->number('perubahan_harga_kondisi[harga][to]', NULL, 'id="perubahan_harga_kondisi-harga-to" class="form-control text-right" data-input-type="number-format" '.(isset($this->form->data('perubahan_harga_kondisi')['harga']) ? '' : 'disabled').'', '') ?>
        </div>
    </div>
</div>