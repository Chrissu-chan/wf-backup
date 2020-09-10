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
                        <?= $this->form->date('periode_awal', date('Y-m-01'), 'class="form-control" data-input-type="datepicker"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{sampai_tanggal}}</label>
                        <?= $this->form->date('periode_akhir', date('Y-m-d'), 'class="form-control" data-input-type="datepicker"') ?>
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
                        <input type="hidden" name="select_supplier" id="select_supplier">
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
                <button type="submit" onclick="getvalue()" class="btn btn-primary">{{cetak}}</button>
            </div>
        </div>
    </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    function getvalue() {
        var supplier = document.getElementById('supplier');
        var supplier_select = supplier.options[supplier.selectedIndex].text;
        let supplier_value = document.querySelector('#select_supplier');
            supplier_value.value = supplier_select;
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>