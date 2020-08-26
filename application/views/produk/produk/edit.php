<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{edit_produk}} <?= $this->localization->lang($model->jenis) ?>
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('produk.produk.update', array('id' => $model->id))) ?>
            <?php $this->template->view('produk/produk/form_'.$model->jenis) ?>
            <div class="form-group">
                <button class="btn btn-success">{{update}}</button>
                <a href="<?= $this->route->name('produk.produk') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('produk/produk/form_script_'.$model->jenis) ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>
