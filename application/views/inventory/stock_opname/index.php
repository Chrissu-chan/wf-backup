<?php $this->template->section('css') ?>
    <link href="<?= base_url('public/plugins/bootstrap-wizard/css/bwizard.min.css') ?>" rel="stylesheet"/>
<?php $this->template->endsection() ?>
<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">{{stck_opname}}</h1>
        </div>
        <div class="col-md-6 text-right">

        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open($this->route->name('inventory.stock_opname.start')) ?>
            <div class="bwizard clearfix">
                <?php $this->template->view('inventory/stock_opname/partials/step_nav', array('active' => 1)) ?>
                <hr>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <div class="form-group">
                            <?= $this->form->select('id_gudang', lists($this->cabang_gudang_m->view('cabang_gudang')->scope('aktif_cabang')->get(), 'id', 'gudang'), $this->session->userdata('cabang')->id, 'class="form-control input-lg"') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->action->submit('create', 'class="btn btn-success pull-right"', $this->localization->lang('start')) ?>
                </div>
            </div>
            <?= $this->form->close(); ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>