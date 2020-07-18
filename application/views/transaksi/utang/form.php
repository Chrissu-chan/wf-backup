<div id="frm-message"></div>
<?= $this->form->hidden('id_cabang', $this->session->userdata('cabang')->id) ?>
<div class="form-group">
    <label>{{no_utang}}</label>
    <?= $this->form->text('no_utang', null, 'id="no_utang" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_utang}}</label>
    <?= $this->form->select('jenis_utang', $this->utang_m->enum('jenis_utang'), null, 'id="jenis_utang" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{no_refrensi}}</label>
    <?= $this->form->text('no_refrensi', null, 'id="no_refrensi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{nama}}</label>
	<?= $this->form->text('nama', null, 'id="no_refrensi" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{tanggal_utang}}</label>
    <div class="input-group">
        <?= $this->form->text('tanggal_utang', date('d-m-Y'), 'id="tanggal_utang" class="form-control" data-input-type="datepicker"') ?>
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    </div>
</div>
<div class="form-group">
    <label>{{tanggal_jatuh_tempo}}</label>
    <div class="input-group">
        <?= $this->form->text('tanggal_jatuh_tempo', date('d-m-Y'), 'id="tanggal_jatuh_tempo" class="form-control" data-input-type="datepicker"') ?>
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    </div>
</div>
<div class="form-group">
    <label>{{jumlah_utang}}</label>
    <div class="input-group">
        <span class="input-group-addon">{{currency}}</span>
        <?= $this->form->text('jumlah_utang', null, 'id="jumlah_utang" class="form-control text-right" data-input-type="number-format"') ?>
    </div>
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