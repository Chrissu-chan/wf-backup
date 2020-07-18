<?php $this->template->section('content') ?>
    <?= $this->form->open($this->route->name('transaksi.unserviced.store'), 'id="frm-unserviced"') ?>
        <?php $this->template->view('transaksi/unserviced/form') ?>
        <div class="form-group text-right">
	        <button class="btn btn-success">{{store}}</button>
            <a href="<?= $this->route->name('transaksi.unserviced') ?>" class="btn btn-default">{{cancel}}</a>
        </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<?php $this->load->view('transaksi/unserviced/form_script') ?>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main_transaksi') ?>