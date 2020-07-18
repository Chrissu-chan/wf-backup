<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{cetak_barcode}}
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open(NULL, 'id="frm-barcode"') ?>
            <?php $this->template->view('barcode/barcode/form') ?>
            <div class="form-group">
                <button class="btn btn-success">{{print}}</button>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('barcode/barcode/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>