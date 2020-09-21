<?php $this->template->section('css') ?>
	<style>
		.overflow-dashboard{
			overflow-y:auto;
			height:480px;
		}
		.overflow-dashboard thead th{
			position:sticky;
			top:0;
			background-color:white;
		}
	</style>
<?php $this->template->endsection() ?>
<?php $this->template->section('content') ?>
	<?php $this->template->view('layouts/partials/message') ?>
	<div class="row">
		<div class="col-md-6">
			<h1 class="page-header">
				{{info_perubahan_harga}}
			</h1>
		</div>
		<div class="col-md-6 text-right">
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="overflow-dashboard">
				<table width="100%" id="table" class="table table-striped table-condensed ">
					<thead>
						<tr>
							<th width="150">{{tanggal}}</th>
							<th width="150">{{nama_cabang}}</th>
							<th width="150">{{kode}}</th>
							<th>{{produk}}</th>
							<th width="100">{{satuan}}</th>
							<th width="100" class="text-center">{{jumlah}}</th>
							<th width="150">{{harga_awal}}</th>
							<th width="150">{{harga_akhir}}</th>
							<th width="100">{{PIC}}</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($broadcast_harga_produk) { ?>
							<?php foreach ($broadcast_harga_produk as $perubahan_harga) { ?>
								<tr height="45">
									<td><?= $this->localization->human_date($perubahan_harga->tanggal) ?></td>
									<td><?= ($perubahan_harga->id_cabang == 0 ? 'General' : $perubahan_harga->nama_cabang) ?></td>
									<td><?= $perubahan_harga->kode ?></td>
									<td><?= $perubahan_harga->produk ?></td>
									<td><?= $perubahan_harga->satuan ?></td>
									<td class="text-center"><?= $perubahan_harga->jumlah ?></td>
									<td class="text-right"><?= $this->localization->number($perubahan_harga->harga_awal) ?></td>
									<td class="text-right <?= ($perubahan_harga->harga_akhir > $perubahan_harga->harga_awal ? 'danger' : 'success') ?>"><?= $this->localization->number($perubahan_harga->harga_akhir) ?></td>
									<td><?= $perubahan_harga->created_by ?></td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td colspan="8" class="text-center">{{no_data_available}}</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h1 class="page-header">
				{{info_margin_laba}}
			</h1>
		</div>
		<div class="col-md-6 text-right">
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="overflow-dashboard">
				<table width="100%" id="table" class="table table-striped table-condensed">
					<thead>
						<tr>
							<th style="position:sticky; top:0;"width="150">{{kode}}</th>
							<th>{{produk}}</th>
							<th width="100">{{cabang}}</th>
							<th width="100">{{satuan}}</th>
							<th width="100" class="text-center">{{jumlah}}</th>
							<th width="100" class="text-center">{{margin}}%</th>
							<th width="100" class="text-center">{{laba}}%</th>
							<th width="150">{{harga_beli_terakhir}}</th>
							<th width="150">{{harga}}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($margin_laba) { ?>
							<?php foreach ($margin_laba as $margin) { ?>
								<?php if ($this->localization->number($margin->laba_persen, 2) < $this->localization->number($margin->margin_persen, 2)) { ?>
									<tr>
										<td><?= $margin->kode ?></td>
										<td><?= $margin->produk ?></td>
										<td><?= $margin->cabang ?></td>
										<td><?= $margin->satuan ?></td>
										<td class="text-center"><?= $margin->jumlah ?></td>
										<td class="text-center"><?= $this->localization->number($margin->margin_persen, 2) ?></td>
										<td class="text-center"><?= $this->localization->number($margin->laba_persen, 2) ?></td>
										<td class="text-right"><?= $this->localization->number($margin->harga_beli_terakhir * $margin->konversi) ?></td>
										<td class="text-right"><?= $this->localization->number($margin->harga) ?></td>
										<td width="1"><a href="<?= $this->route->name('produk.pengaturan_harga.edit', array('id' => $margin->id)) ?>" class="btn btn-primary btn-sm">{{pengaturan_harga}}</a></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td colspan="8" class="text-center">{{no_data_available}}</td>
							</tr>
						<?php } ?>
					</tbody>
			    </table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h1 class="page-header">
				{{barang_expired}} (6 bulan)
			</h1>
		</div>
		<div class="col-md-6 text-right">
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="m-t-15">
				<ul class="nav nav-pills" role="tablist">
					<li class="active"><a href="#detail_barang_expired" role="tab" data-toggle="tab">{{barang_expired}}</a></li>
					<li><a href="#detail_barang_expired_ignore" role="tab" data-toggle="tab">{{barang_expired_ignore}}</a></li>
				</ul>
				<div class="tab-content p-r-0 p-l-0 p-b-0">
					<div role="tabpanel" class="tab-pane active" id="detail_barang_expired">
                        <div class="form-group">
                            <button type="button" id="btn-hide_expired" class="btn btn-primary">{{hide}}</button>
                        </div>
						<?= $this->form->open(NULL, 'id="frm-barang_expired"') ?>
						<table width="100%" id="table-barang_expired" class="table table-striped table-condensed ">
							<thead>
								<tr>
									<th width="1"><input type="checkbox" id="select_all_expired"></th>
									<th width="100">{{kode_barang}}</th>
									<th>{{nama_barang}}</th>
									<th width="100">{{satuan}}</th>
									<th width="150">{{expired}}</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						<?= $this->form->close() ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="detail_barang_expired_ignore">
                        <div class="form-group">
                            <button type="button" id="btn-show_expired" class="btn btn-primary">{{show}}</button>
                        </div>
						<?= $this->form->open(NULL, 'id="frm-barang_expired_ignore"') ?>
						<table width="100%" id="table-barang_expired_ignore" class="table table-striped table-condensed ">
							<thead>
								<tr>
									<th width="1"><input type="checkbox" id="select_all_ignore"></th>
									<th width="100">{{kode_barang}}</th>
									<th>{{nama_barang}}</th>
									<th width="100">{{satuan}}</th>
									<th width="150">{{expired}}</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
						<?= $this->form->close() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
	<script>
		var dataTableBarangExpired;
		$(function() {
			dataTableBarangExpired = $('#table-barang_expired').DataTable({
				processing: true,
				serverSide: true,
				autoWidth: false,
				searchDelay: 1000,
				ajax: '<?= $this->url_generator->current_url() ?>?type=expired',
				columns: [
					{data : 'id_barang', searchable : false, orderable : false, render : function(data, type, row) {
						return '<input type="checkbox" name="barang['+data+'][id_barang]" value="'+data+'" /><input type="hidden" name="barang['+data+'][expired]" value="'+row.expired+'" />';
					}},
					{data: 'kode_barang', name: 'barang.kode'},
					{data: 'nama_barang', name: 'barang.nama'},
					{data: 'satuan', name: 'satuan.satuan'},
					{data: 'expired_desc', name: 'expired.expired'}
				],
				order: [[1, 'ASC']]
			});

			dataTableBarangExpiredIgnore = $('#table-barang_expired_ignore').DataTable({
				processing: true,
				serverSide: true,
				autoWidth: false,
				searchDelay:1000,
				ajax: '<?= $this->url_generator->current_url() ?>?type=ignore',
				columns: [
					{data : 'id', searchable : false, orderable : false, render : function(data, type, row) {
                        return '<input type="checkbox" name="id[]" value="'+data+'" />';
					}},
					{data: 'kode_barang', name: 'barang.kode'},
					{data: 'nama_barang', name: 'barang.nama'},
					{data: 'satuan', name: 'satuan.satuan'},
					{data: 'expired_desc', name: 'expired.expired'}
				],
				order: [[1, 'ASC']]
			});

			$('#select_all_expired').click(function(){
				var rows = dataTableBarangExpired.rows().nodes();
				$('input[type="checkbox"]:not(:disabled)', rows).prop('checked', this.checked);
			});

			$('#select_all_ignore').click(function(){
				var rows = dataTableBarangExpiredIgnore.rows().nodes();
				$('input[type="checkbox"]:not(:disabled)', rows).prop('checked', this.checked);
			});
			
			$('#btn-hide_expired').click(function () {
				swalConfirm('Apakah anda yakin akan menyembunyikan data ini?', function() {
					$.ajax({
						url: '<?= $this->route->name('dashboard.hide_expired') ?>',
                        type: 'post',
                        data: $('#frm-barang_expired').serialize(),
						success: function(response) {
							if (response.success) {
								$.growl.notice({message: response.message});
								dataTableBarangExpired.ajax.reload();
								dataTableBarangExpiredIgnore.ajax.reload();
							} else {
								$.growl.error({message: response.message});
							}
						}
					});
				});
			});

			$('#btn-show_expired').click(function () {
				swalConfirm('Apakah anda yakin akan menampilkan data ini?', function() {
					$.ajax({
						url: '<?= $this->route->name('dashboard.show_expired') ?>',
						type: 'post',
						data: $('#frm-barang_expired_ignore').serialize(),
						success: function(response) {
							if (response.success) {
								$.growl.notice({message: response.message});
								dataTableBarangExpired.ajax.reload();
								dataTableBarangExpiredIgnore.ajax.reload();
							} else {
								$.growl.error({message: response.message});
							}
						}
					});
				});
			});
		});
	</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>