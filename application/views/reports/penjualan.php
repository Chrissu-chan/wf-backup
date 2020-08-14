<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{report}} {{penjualan}}
            </h1>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <?= $this->form->open($this->route->name('reports.penjualan.excel')) ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{mulai_tanggal}}</label>
                        <?= $this->form->date('periode_awal', date('Y-m-01'), 'class="form-control" id="periode_awal"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{sampai_tanggal}}</label>
                        <?= $this->form->date('periode_akhir', date('Y-m-d'), 'class="form-control" id="periode_akhir"') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{rekap}}</label>
                        <?= $this->form->select('rekap', $rekap, null, 'class="form-control"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{shift}}</label>
                        <?= $this->form->select('shift', lists($this->shift_waktu_m->scope('kasir')->get(), 'id', 'shift_waktu', true), null, 'class="form-control"') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{kasir}}</label>
                        <?= $this->form->select('kasir', lists($this->user_cabang_m->view('users')->scope('cabang_aktif')->get(), 'username', 'name', true), null, 'class="form-control"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{jenis_produk}}</label>
                        <?= $this->form->select('jenis_produk', ['' => $this->localization->lang('select_placeholder')]+$this->produk_m->enum('jenis'), null, 'class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">{{cetak}}</button>
            </div>
        </div>
    </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function(){
        $('#periode_awal').inputmask('99-99-9999');
        $('#periode_akhir').inputmask('99-99-9999');
    });
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>