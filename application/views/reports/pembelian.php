<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{report}} {{pembelian}}
            </h1>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <?= $this->form->open($this->route->name('reports.pembelian.excel')) ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{mulai_tanggal}}</label>
                        <?= $this->form->date('periode_awal', date('Y-m-01'), 'class="form-control" id="periode_awal" data-input-type="dateinput"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{sampai_tanggal}}</label>
                        <?= $this->form->date('periode_akhir', date('Y-m-d'), 'class="form-control" id="periode_akhir" data-input-type="dateinput"') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{rekap}}</label>
                        <?= $this->form->select('rekap', $rekap, null, 'class="form-control"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{supplier}}</label>
                        <?= $this->form->select('supplier', lists($this->supplier_cabang_m->view('supplier')->scope('cabang_aktif')->get(), 'id', 'supplier', true), null, 'class="form-control" id="supplier"') ?>
                        <input type="hidden" name="supplier_desc" id="supplier_desc" value="all_supplier"> 
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{user}}</label>
                        <?= $this->form->select('user', lists($this->user_cabang_m->view('users')->scope('cabang_aktif')->get(), 'username', 'name', true), null, 'class="form-control"') ?>
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
    $('#supplier').change(function() {
        if ($('#supplier').val()) {
            $('#supplier_desc').val($('#supplier option:selected').text());
        }
    })
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>