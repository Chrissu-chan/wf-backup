<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{application_config}}
                <small><i class="fa fa-angle-right"></i> <?= $application->application ?></small>
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <?= $this->action->button('create', 'class="btn btn-primary btn-block" onclick="create()"') ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->url_generator->current_url().'/save', 'id="frm-edit"') ?>
            <div class="form-group">
                <label><?= $this->config->item('application_name') ?></label>
                <?= $this->form->text('application_name', null, 'class="form-control"') ?>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">{{save}}</button>
                <a href="<?= $this->route->name('developers.applications') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/developer') ?>