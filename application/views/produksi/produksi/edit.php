<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{update_produksi}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('produksi.produksi.update', array('id' => $model->id))) ?>
            <?php $this->template->view('produksi/produksi/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{update}}</button>
                <a href="<?= $this->route->name('produksi.produksi') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('produksi/produksi/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>