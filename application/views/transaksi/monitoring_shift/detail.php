<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{detail_monitoring_shift}}
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, null, null) ?>
	            <div class="row">
	                <div class="col-md-6">
		                <div class="form-group">
			                <label>{{shift}}</label>
			                <?= $this->form->text('shift_waktu', null, 'id="shift_waktu" class="form-control" readonly') ?>
		                </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="form-group">
	                        <label>{{penanggung_jawab}}</label>
	                        <?= $this->form->text('name', null, 'id="name" class="form-control" readonly') ?>
	                    </div>
	                </div>
                </div>
		        <table width="100%" class="table table-bordered table-condensed">
			        <tbody>
			            <tr>
				            <th colspan="2" class="text-center">{{rekap_transaksi}}</th>
			            </tr>
				        <tr>
					        <td>{{uang_awal}}</td>
					        <td width="200" class="text-right"><?= $this->localization->number($model->uang_awal) ?></td>
				        </tr>
				        <tr>
					        <td>{{penjualan}}</td>
					        <td class="text-right"><?= $this->localization->number($model->total_penjualan) ?></td>
				        </tr>
				        <tr>
					        <td>{{uang_masuk}}</td>
					        <td class="text-right"><?= $this->localization->number($model->total_pemasukan) ?></td>
				        </tr>
				        <tr>
					        <td>{{uang_keluar}}</td>
					        <td class="text-right"><?= $this->localization->number($model->total_pengeluaran) ?></td>
				        </tr>
				        <tr>
					        <th>{{total}}</th>
					        <th class="text-right">
						        <?php $total = $model->uang_awal + $model->total_penjualan + $model->total_pemasukan + $model->total_pengeluaran ?>
						        <?= $this->localization->number($total) ?>
					        </th>
				        </tr>
			            <?php if (!$model->active) { ?>
					        <tr>
						        <td>{{uang_akhir}}</td>
						        <td class="text-right"><?= $this->localization->number($model->uang_akhir) ?></td>
					        </tr>
					        <tr>
						        <th>{{selisih}}</th>
						        <th class="text-right">
							        <?php $selisih = $model->uang_akhir - $total ?>
							        <?= $this->localization->number($selisih) ?>
						        </th>
					        </tr>
			            <?php } ?>
			        </tbody>
		        </table>
            <?= $this->form->close() ?>
	        <hr>
	        <table width="100%" id="data-table" class="table table-bordered table-condensed">
		        <thead>
			        <tr>
				        <th width="150">{{kode_barang}}</th>
				        <th>{{nama_barang}}</th>
				        <th width="150">{{satuan}}</th>
				        <th width="100" class="text-right">{{stok_awal}}</th>
				        <th width="100" class="text-right">{{mutasi}}</th>
				        <th width="100" class="text-right">{{stok_akhir}}</th>
				        <th width="150">{{total_penjualan}}</th>
			        </tr>
		        </thead>
	        </table>
        </div>
    </div>
<?php $this->template->endsection() ?>
<?php $this->template->section('page_script') ?>
	<script>
		var dataTable;

		$(function() {
			dataTable = $('#data-table').DataTable({
				processing: true,
				serverSide: true,
				ajax: '<?= $this->url_generator->current_url() ?>',
				columns: [
					{data: 'kode', name: 'barang.kode'},
					{data: 'nama', name: 'barang.nama'},
					{data: 'satuan', name: 'satuan.satuan'},
					{data: 'stok_awal', name: 'shift_aktif_stok.stok_awal', searchable: false, orderable: false, class: 'text-right nowrap'},
					{data: 'mutasi', name: 'shift_aktif_stok.mutasi', searchable: false, orderable: false, class: 'text-right nowrap'},
					{data: 'stok_akhir', name: 'shift_aktif_stok.stok_akhir', searchable: false, orderable: false, class: 'text-right nowrap'},
					{data: 'total_penjualan', name: 'penjualan.total', searchable: false, class: 'text-right nowrap'}
				]
			});
		});
	</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>
