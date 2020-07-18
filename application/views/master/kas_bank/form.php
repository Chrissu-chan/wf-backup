<div id="frm-message"></div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_kas_bank}}</label>
    <?= $this->form->select('jenis_kas_bank', $this->kas_bank_m->enum('kas_bank'), null, 'onchange="select_kas_bank()" id="jenis_kas_bank" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{cabang}}</label>
    <?= $this->form->select('cabang[]', lists($this->cabang_m->scope('auth')->get() , 'id', 'nama'), null, 'id="roles" class="form-control" style="width:100%;"') ?>
</div>
<div class="form-group" id="form-group-id_bank">
    <label>{{bank}}</label>
    <?= $this->form->select('id_bank', lists($this->bank_m->get(), 'id', 'bank', 'pilih_bank'), null, 'id="id_bank" class="form-control"') ?>
</div>
<div class="form-group" id="form-group-nomor_rekening">
    <label>{{nomor_rekening}}</label>
    <?= $this->form->text('nomor_rekening', null, 'id="nomor_rekening" class="form-control"') ?>
</div>
<div class="form-group" id="form-group-nama_rekening">
    <label>{{nama_rekening}}</label>
    <?= $this->form->text('nama_rekening', null, 'id="nama_rekening" class="form-control"') ?>
</div>
<script>
    $(function() {
        select_kas_bank();
    });

    function select_kas_bank() {
        if($('#jenis_kas_bank').val() == 'kas') {
            $('#form-group-id_bank').hide();
            $('#form-group-nomor_rekening').hide();
            $('#form-group-nama_rekening').hide();
            $('#nomor_rekening').val('');
            $('#nama_rekening').val('');
        } else {
            $('#form-group-id_bank').show();
            $('#form-group-nomor_rekening').show();
            $('#form-group-nama_rekening').show();
        }
    }
</script>