<?php $this->template->section('content') ?>
	<div class="row">
		<div class="col-md-6">
			<h1 class="page-header">
				{{kartu_stok}}
			</h1>
		</div>
		<div class="col-md-6 text-right">
			<div class="form-group">
				<?= $this->action->link('view', $this->route->name('inventory.stok.export', array('id' => $model->id_barang, 'gudang' => $gudang, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir)), 'class="btn btn-primary"', $this->localization->lang('cetak')) ?>
			</div>
		</div>
	</div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, null, null) ?>
            <div class="form-group">
                <label>{{gudang}}</label>
                <?= $this->form->text('gudang', null, 'id="gudang" class="form-control" readonly') ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{kode_barang}}</label>
                        <?= $this->form->text('kode_barang', null, 'id="kode_barang" class="form-control" readonly') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{nama_barang}}</label>
                        <?= $this->form->text('nama_barang', null, 'id="nama_barang" class="form-control" readonly') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{satuan}}</label>
                        <?= $this->form->text('satuan', null, 'id="satuan" class="form-control" readonly') ?>
                    </div>
                </div>
            </div>
            <?= $this->form->close() ?>
            <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                <thead>
                    <tr>
                        <th width="150">{{tanggal_mutasi}}</th>
                        <th width="150">{{no_nota}}</th>
	                    <th width="150">{{batch_number}}</th>
	                    <th width="150">{{expired_date}}</th>
                        <th width="100">{{jenis_mutasi}}</th>
                        <th>{{keterangan}}</th>
                        <th width="75" class="text-right">{{masuk}}</th>
                        <th width="75" class="text-right">{{keluar}}</th>
                        <th width="75" class="text-right">{{sisa}}</th>
                        <th width="100">{{petugas}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8">{{stok_awal}}</td>
                        <td class="text-right"><?= $model->stok_awal ?></td>
                    </tr>
                    <?php $total_mutasi = $model->stok_awal; ?>
                    <?php foreach($model->mutasi as $mutasi) { ?>
	                    <?php $total_mutasi += $mutasi->jumlah ?>
                        <tr>
                            <td><?= $this->localization->human_date($mutasi->tanggal_mutasi) ?></td>
                            <td><a href="#" onclick="view('<?= $mutasi->jenis_mutasi ?>', '<?= $mutasi->id_transaksi ?>')"><?= $mutasi->no_nota ?></a></td>
                            <td><?= $mutasi->batch_number ?></td>
                            <td><?= $this->localization->human_date($mutasi->expired) ?></td>
                            <td><?= $mutasi->jenis_mutasi ?></td>
                            <td><?= $mutasi->keterangan ?></td>
                            <td class="text-right"><?= ($mutasi->jumlah > 0 ? $mutasi->jumlah : '') ?></td>
                            <td class="text-right"><?= ($mutasi->jumlah < 0 ? ($mutasi->jumlah * -1) : '') ?></td>
                            <td class="text-right"><?= $this->localization->number($total_mutasi) ?></td>
	                        <td><?= $mutasi->created_by ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">{{stok_akhir}}</td>
                        <td class="text-right"><?= $this->localization->number($total_mutasi) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>
<?php $this->template->section('page_script') ?>
	<script>
		function view(jenis_mutasi, id) {
			var url;
			if (jenis_mutasi == 'pembelian') {
				url = '<?= $this->route->name('transaksi.pembelian') ?>/view/'+id;
			} else  {
				url = '<?= $this->route->name('transaksi.penjualan') ?>/view/'+id;
			}
			$.ajax({
				url: url,
				success: function(response) {
					bootbox.dialog({
						title: '{{view}} {{transaksi}}',
						size: 'large',
						message: response
					});
				}
			});
		}
	</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>