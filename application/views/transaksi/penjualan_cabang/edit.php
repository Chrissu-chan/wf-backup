<?php $this->template->section('content') ?>
    <?= $this->form->model($model, $this->route->name('transaksi.penjualan.update', array('id' => $model->id)), 'id="frm-penjualan"') ?>
        <?php $this->template->view('transaksi/penjualan_cabang/form') ?>
        <div class="form-group text-right">
	        <a href="#modal-dialog" class="btn btn-success" data-toggle="modal">{{pembayaran}}</a>
            <a href="<?= $this->route->name('transaksi.penjualan') ?>?jenis_penjualan=cabang" class="btn btn-default">{{cancel}}</a>
        </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('transaksi/penjualan_cabang/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main_transaksi') ?>
