<div id="frm-message"></div>
<?= $this->form->hidden('id_cabang', $CI->session->userdata('cabang')->id, 'id="id_cabang"') ?>
<?= $this->form->hidden('created_by', $CI->session->userdata('cabang')->nama, 'id="created_by"') ?>
<?= $this->form->hidden('tanggal_daftar', date('d-m-Y'), 'id="tanggal_daftar"') ?>
<div class="form-group">
    <label>{{kode}}</label>
    <?= $this->form->text('kode', null, 'id="kode" class="form-control"') ?>
</div>
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
    <?= $this->form->select('jenis_kelamin', $this->member_m->enum('jenis_kelamin'), null, 'id="jenis_kelamin" class="form-control"') ?>
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
    <?= $this->form->select('id_kota', lists($this->kota_m->get(), 'id', 'kota', '- Pilih Kota -'), null, 'id="id_kota" class="form-control" data-input-type="select2" style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{alamat}}</label>
    <?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_member}}</label>
    <?= $this->form->select('id_jenis_member', lists($this->jenis_member_m->get(), 'id', 'jenis_member'), null, 'id="id_jenis_member" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{tanggal_daftar}}</label>
    <div class="input-group input-group-sm">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <?= $this->form->date('tanggal_daftar', date('d-m-Y'), 'id="tanggal_daftar" class="form-control" data-input-type="datepicker"') ?>
    </div>
</div>
<div class="form-group">
    <label>{{tanggal_expired}}</label>
    <div class="input-group input-group-sm">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <?= $this->form->date('tanggal_expired', date('d-m-Y'), 'id="tanggal_expired" class="form-control" data-input-type="datepicker"') ?>
    </div>
</div>

<script>
    function find_pelanggan() {
        id = $('#id_pelanggan').val();
        if(id) {
            $.ajax({
                url: '<?= $this->route->name('pelanggan.pelanggan.find_json') ?>?id='+id,
                success: function(response) {
                    if (response.success) {
                        $('#nama').val(response.data.nama).prop('disabled', true);
                        $('#id_jenis_identitas').val(response.data.id_jenis_identitas).change().prop('disabled', true);
                        $('#no_identitas').val(response.data.no_identitas).prop('disabled', true);
                        $('#jenis_kelamin').val(response.data.jenis_kelamin).change().prop('disabled', true);
                        $('#telepon').val(response.data.telepon).prop('disabled', true);
                        $('#hp').val(response.data.hp).prop('disabled', true);
                        $('#id_kota').val(response.data.id_kota).change().prop('disabled', true);
                        $('#alamat').val(response.data.alamat).prop('disabled', true);
                    }
                }
            });
        } else {
            $('#nama').val('').prop('disabled', false);
            $('#id_jenis_identitas').val('').change().prop('disabled', false);
            $('#no_identitas').val('').prop('disabled', false);
            $('#jenis_kelamin').val('').change().prop('disabled', false);
            $('#telepon').val('').prop('disabled', false);
            $('#hp').val('').prop('disabled', false);
            $('#id_kota').val('').change().prop('disabled', false);
            $('#alamat').val('').prop('disabled', false);
        }
    }
</script>