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
			<table width="100%" id="table" class="table table-bordered table-condensed ">
				<thead>
					<tr>
						<th width="150">{{tanggal}}</th>
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
							<tr>
								<td><?= $this->localization->human_date($perubahan_harga->tanggal) ?></td>
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
			<table width="100%" id="table" class="table table-bordered table-condensed ">
				<thead>
				<tr>
					<th width="150">{{kode}}</th>
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
<?php $this->template->endsection() ?>
<?php $this->template->view('layouts/main') ?>