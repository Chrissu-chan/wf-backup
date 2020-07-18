<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{create_perubahan_harga}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open($this->route->name('produk.perubahan_harga.store')) ?>
            <?php $this->template->view('produk/perubahan_harga/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{store}}</button>
                <a href="<?= $this->route->name('produk.perubahan_harga') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('produk/perubahan_harga/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>