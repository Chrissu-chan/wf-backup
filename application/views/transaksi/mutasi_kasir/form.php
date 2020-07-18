<div id="frm-message"></div>
<div class="form-group">
    <label>{{tipe}}</label>
    <?= $this->form->select('tipe', array('' => $this->localization->lang('select'))+$this->mutasi_kasir_m->enum('tipe'), null, 'id="tipe" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{no_mutasi}}</label>
    <?= $this->form->text('no_mutasi', null, 'id="no_mutasi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{no_referensi}}</label>
    <?= $this->form->text('no_referensi', null, 'id="no_referensi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{tanggal_mutasi}}</label>
    <div class="input-group input-group-sm">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <?= $this->form->date('tanggal_mutasi', date('d-m-Y'), 'id="tanggal_mutasi" class="form-control" data-input-type="datepicker"') ?>
    </div>
</div>
<div id="form-group-jenis_transaksi" class="form-group">
    <?php if ($this->form->data('tipe') == 'pemasukan') { ?>
        <label>{{jenis_transaksi}}</label>
        <?= $this->form->select('id_jenis_transaksi', lists($this->jenis_transaksi_m->scope('pemasukan')->get(), 'id', 'jenis_transaksi', $this->localization->lang('select')), null, 'id="id_jenis_transaksi" class="form-control"') ?>
    <?php } else if ($this->form->data('tipe') == 'pengeluaran') { ?>
        <label>{{jenis_transaksi}}</label>
        <?= $this->form->select('id_jenis_transaksi', lists($this->jenis_transaksi_m->scope('pengeluaran')->get(), 'id', 'jenis_transaksi', $this->localization->lang('select')), null, 'id="id_jenis_transaksi" class="form-control"') ?>
    <?php } ?>
</div>
<div class="form-group">
    <label>{{nominal}}</label>
    <?= $this->form->text('nominal', null, 'id="nominal" class="form-control" data-input-type="number-format" style="direction:rtl"') ?>
</div>
<div class="form-group">
    <label>{{file}}</label>
    <div class="input-group input-group-sm">
        <?= $this->form->text('file', null, 'id="file" class="form-control"  onclick="choose_file()" readonly') ?>
        <?= $this->form->upload('file_path', null, 'id="file_path" onchange="select_file()" style="display: none;"') ?>
        <span class="input-group-btn">
            <button type="button" class="btn btn-danger" onclick="delete_file()"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-default" onclick="choose_file()">...</button>
        </span>
    </div>
</div>
<div class="form-group">
    <label>{{keterangan}}</label>
    <?= $this->form->text('keterangan', null, 'id="keterangan" class="form-control"') ?>
</div>

<script>
    function choose_file() {
        $('#file_path').click();
    }

    function select_file() {
        $('#file').val($('#file_path').val().split('\\').pop());
    }

    function delete_file() {
        $('#file_path').val('');
        $('#file').val('');
    }
</script>