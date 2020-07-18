<div id="frm-message"></div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_petugas}}</label>
    <?= $this->form->select('id_jenis_petugas', lists($this->jenis_petugas_m->get(), 'id', 'jenis_petugas'), null, 'id="id_jenis_petugas" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{cabang}}</label>
    <?= $this->form->select('id_cabang', lists($this->cabang_m->scope('auth')->get(), 'id', 'nama'), isset($model) ? $model->id_cabang : $this->session->userdata('cabang')->id, 'class="form-control" id="id_cabang"') ?>
</div>