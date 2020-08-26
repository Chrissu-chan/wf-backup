<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{shift}} <small><i class="fa fa-angle-right"></i> {{edit}}</small>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('master.shift.update', array('id' => $model->id))) ?>
                <?php $this->template->view('master/shift/form') ?>
                <div class="form-group">
                    <button class="btn btn-success">{{update}}</button>
                    <a href="<?= $this->route->name('master.shift') ?>" class="btn btn-default">{{cancel}}</a>
                </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
    <?php $this->load->view('master/shift/form_page_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>
