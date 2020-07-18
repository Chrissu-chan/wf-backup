<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{update_pengaturan_harga_produk}} <?= $this->localization->lang($model->jenis) ?>
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('produk.pengaturan_harga.update', array('id' => $model->id))) ?>
            <?php $this->template->view('produk/pengaturan_harga/form_'.$model->jenis) ?>
            <div class="form-group">
                <button class="btn btn-success">{{update}}</button>
                <a href="<?= $this->route->name('produk.pengaturan_harga') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('produk/pengaturan_harga/form_script_'.$model->jenis) ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>