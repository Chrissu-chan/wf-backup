<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{update_diskon}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('produk.diskon.update', array('id' => $model->id))) ?>
            <?php $this->template->view('produk/diskon/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{update}}</button>
                <a href="<?= $this->route->name('produk.diskon') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('produk/diskon/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>