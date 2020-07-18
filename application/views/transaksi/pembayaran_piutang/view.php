<div class="row">
	<div class="col-md-6">
		<table width="100%" class="table table-profile">
			<tr>
				<td class="field">{{tanggal_bayar}}</td>
				<td><?= $this->localization->human_date($model->tanggal_bayar) ?></td>
			</tr>
			<tr>
				<td class="field">{{jumlah_bayar}}</td>
				<td><?= $this->localization->number($model->jumlah_bayar) ?></td>
			</tr>
			<tr>
				<td class="field">{{dibayarkan_dari}}</td>
				<td>
					<?php if($model->jenis_kas_bank == 'bank') { ?>
						<?= 'Metode Bank <b>' . $model->bank . ' </b>  : ' . $model->nomor_rekening . ' a/n ' . $model->nama_rekening . ' (' . $model->bank . ')' ?>
					<?php } else { ?>
						<?= 'Metode Kas <b>' . $model->kas_bank ?>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="field">{{no_ref_pembayaran}}</td>
				<td><?= $model->no_ref_pembayaran ?></td>
			</tr>
			<tr>
				<td class="field">{{file}}</td>
				<td>
					<?php if(!is_null($model->file)) { ?>
						<?= $this->action->link('view', $this->route->name('transaksi.pembayaran_piutang.download_file', array('id' => $model->id)), 'class="btn btn-xs btn-primary"', $this->localization->lang('download_file')) ?>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="field">{{keterangan}}</td>
				<td><?= $model->keterangan ?></td>
			</tr>
		</table>
	</div>
	<div class="col-md-6">
		<?php if ($model->batal) { ?>
			<table width="100%" class="table table-profile">
				<tr>
					<td class="field">{{jenis_batal}}</td>
					<td><?= $this->pembayaran_piutang_m->enum('jenis_batal', $model->jenis_batal) ?></td>
				</tr>
				<tr>
					<td class="field">{{deleted_by}}</td>
					<td><?= $model->deleted_by ?></td>
				</tr>
				<tr>
					<td class="field">{{deleted_at}}</td>
					<td><?= $this->localization->human_datetime($model->deleted_at) ?></td>
				</tr>
			</table>
		<?php } ?>
	</div>
</div>
