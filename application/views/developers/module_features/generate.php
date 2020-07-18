<?php $this->template->section('content') ?>
    <h1 class="page-header">
        CRUD Generator
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="generate_store" method="post">
                <?php $this->template->view('layouts/partials/validation') ?>
                <div class="form-group">
                    <label>Table</label>
                    <input type="text" name="table" class="form-control">
                </div>
                <div class="form-group">
                    <label>Exception Columns</label>
                    <input type="text" name="exception_columns" class="form-control">
                </div>
                <div class="form-group">
                    <label>Module</label>
                    <input type="text" name="module" value="<?= ($module) ? strtolower($module->module) : '' ?>" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/developer') ?>