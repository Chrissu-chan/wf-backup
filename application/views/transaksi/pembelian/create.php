<?php $this->template->section('content') ?>
    <?= $this->form->open($this->route->name('transaksi.pembelian.store'), 'id="frm-pembelian"') ?>
        <?php $this->template->view('transaksi/pembelian/form') ?>
        <div class="form-group text-right">
	        <a href="#modal-dialog" class="btn btn-success" data-toggle="modal">{{pembayaran}}</a>
            <a href="<?= $this->route->name('transaksi.pembelian') ?>" class="btn btn-default">{{cancel}}</a>
        </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('transaksi/pembelian/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main_transaksi') ?>