<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{create_barang_produksi}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open($this->route->name('master.barang_produksi.store')) ?>
            <?php $this->template->view('master/barang_produksi/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{store}}</button>
                <a href="<?= $this->route->name('master.barang_produksi') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('master/barang_produksi/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>