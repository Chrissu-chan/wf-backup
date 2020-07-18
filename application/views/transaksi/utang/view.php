<?php $this->template->section('content') ?>
<h1 class="page-header">
    {{view}} {{utang}}
</h1>
<?php $this->template->view('layouts/partials/message') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{no_utang}}</label>
                    <?= $this->form->text('no_utang', $model->no_utang, 'id="no_utang" class="form-control" disabled') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{jenis_utang}}</label>
                    <?= $this->form->text('jenis_utang', $this->utang_m->enum('jenis_utang', $model->jenis_utang), 'id="jenis_utang" class="form-control" disabled') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{no_refrensi}}</label>
                    <?= $this->form->text('no_ref', $model->no_ref, 'id="no_ref" class="form-control" disabled') ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{nama}}</label>
                    <?= $this->form->text('nama', $model->nama, 'id="nama" class="form-control" disabled') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{tanggal_utang}}</label>
                    <div class="input-group">
                        <?= $this->form->text('tanggal_utang', date('d-m-Y', strtotime($model->tanggal_utang)), 'id="tanggal_utang" class="form-control" disabled') ?>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{tanggal_jatuh_tempo}}</label>
                    <div class="input-group">
                        <?= $this->form->text('tanggal_jatuh_tempo', date('d-m-Y', strtotime($model->tanggal_jatuh_tempo)), 'id="tanggal_jatuh_tempo" class="form-control" disabled') ?>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{jumlah_utang}}</label>
                    <div class="input-group">
                        <span class="input-group-addon">{{currency}}</span>
                        <?= $this->form->text('jumlah_utang', $this->localization->number($model->jumlah_utang), 'id="jumlah_utang" class="form-control text-right"  disabled') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{jumlah_bayar}}</label>
                    <div class="input-group">
                        <span class="input-group-addon">{{currency}}</span>
                        <?= $this->form->text('jumlah_bayar', $this->localization->number($model->jumlah_bayar), 'id="jumlah_bayar" class="form-control text-right" disabled') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{sisa_utang}}</label>
                    <div class="input-group">
                        <span class="input-group-addon">{{currency}}</span>
                        <?= $this->form->text('sisa_utang', $this->localization->number($model->sisa_utang), 'id="sisa_utang" class="form-control text-right" disabled') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{keterangan}}</label>
                    <?= $this->form->text('keterangan', $model->keterangan, 'id="keterangan" class="form-control" disabled') ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{file}}</label>
                    <br>
                    <?php if ($model->file): ?>
                        <?= $this->action->link('view', $this->route->name('transaksi.utang.download_file', array('id' => $model->id)), 'class="btn btn-sm btn-primary"', $this->localization->lang('download_file')) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="active"><a href="#" role="tab" data-toggle="tab">{{pembayaran_utang}}</a></li>
        </ul>
        <div class="tab-content p-r-0 p-l-0 p-b-0">
            <div role="tabpanel" class="tab-pane active" id="wappr">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="150">{{tanggal_bayar}}</th>
                            <th width="150">{{jumlah_bayar}}</th>
                            <th width="150">{{dibayar_dari}}</th>
                            <th width="150">{{no_ref_pembayaran}}</th>
                            <th>{{keterangan}}</th>
                            <th width="100">{{file}}</th>
                            <!--<th width="100"></th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pembayaran_utang): ?>
                        <?php foreach ($pembayaran_utang as $pembayaran): ?>
                            <tr>
                                <td><?= $this->localization->human_date($pembayaran->tanggal_bayar) ?></td>
                                <td class="text-right"><?= $this->localization->number($pembayaran->jumlah_bayar) ?></td>
                                <td>
                                    <?php if ($pembayaran->jenis_kas_bank == 'bank'): ?>
                                        <?= 'Bank <b>' . $pembayaran->kas_bank . ' </b>  : ' . $pembayaran->nomor_rekening . ' a/n ' . $pembayaran->nama_rekening . ' (' . $pembayaran->bank . ')' ?>
                                    <?php else: ?>
                                        <?= 'Kas <b>' . $pembayaran->kas_bank ?>                
                                    <?php endif ?>
                                </td>
                                <td><?= $pembayaran->no_ref_pembayaran ?></td>
                                <td><?= $pembayaran->keterangan ?></td>
                                <td>
                                    <?php if ($pembayaran->file): ?>
                                        <?= $this->action->link('view', $this->route->name('transaksi.utang.download_file', array('id' => $model->id)), 'class="btn btn-xs btn-primary"', $this->localization->lang('download_file')) ?>
                                    <?php endif ?>
                                </td>
                                <!--<td><?/*= $this->action->button('delete', 'class="btn btn-danger btn-sm" onclick="bayar_delete(\''.$pembayaran->id.'\')"') */?></td>-->
                            </tr>
                        <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">{{belum_ada_pembayaran_utang}}</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    function bayar_delete(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= base_url() ?>transaksi/utang/bayar_delete/'+id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({message: response.message});
                        bootbox.hideAll();
                        document.location.reload();
                    } else {
                        $.growl.notice({message: response.message});
                    }
                }
            });
        });
    }
</script>
<?php $this->template->endsection() ?>
<?php $this->template->view('layouts/main') ?>
