<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{edit_jasa}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('master.jasa.update', array('id' => $model->id))) ?>
            <?php $this->template->view('master/jasa/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{update}}</button>
                <a href="<?= $this->route->name('master.jasa') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
		</div>
	</div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('master/jasa/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>
