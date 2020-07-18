<div class="row">
	<div class="col-md-6">
		<table width="100%" class="table table-profile">
			<tr>
				<td class="field">{{no_mutasi}}</td>
				<td><?= $model->no_mutasi ?></td>
			</tr>
			<tr>
				<td class="field">{{no_referensi}}</td>
				<td><?= $model->no_referensi ?></td>
			</tr>
			<tr>
				<td class="field">{{tanggal_mutasi}}</td>
				<td><?= $this->localization->human_date($model->tanggal_mutasi) ?></td>
			</tr>
			<tr>
				<td class="field">{{jenis_transaksi}}</td>
				<td>
					<?= $model->jenis_transaksi ?>
				</td>
			</tr>
			<tr>
				<td class="field">{{nominal}}</td>
				<td><?= $this->localization->number($model->nominal) ?></td>
			</tr>
			<tr>
				<td class="field">{{file}}</td>
				<td>
					<?php if(!is_null($model->file)) { ?>
						<?= $this->action->link('transaksi.pemasukan.download_file', $this->route->name('transaksi.pemasukan.download_file', array('id' => $model->id)), 'class="btn btn-xs btn-primary"') ?>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="field">{{keterangan}}</td>
				<td><?= $model->keterangan ?></td>
			</tr>
			<!--<tr>
        <td class="field">{{status}}</td>
        <td>
            <?php /*if($model->status == 'approved') { */?>
                <span class="label label-success"><?/*= $this->localization->lang('approved') */?></span>
            <?php /*} else {  */?>
                <span class="label label-warning"><?/*= $this->localization->lang('waiting_approval') */?></span>
            <?php /*} */?>
        </td>
    </tr>-->
		</table>
	</div>
	<div class="col-md-6">
		<?php if ($model->batal) { ?>
			<table width="100%" class="table table-profile">
				<tr>
					<td class="field">{{jenis_batal}}</td>
					<td><?= $this->mutasi_kasir_m->enum('jenis_batal', $model->jenis_batal) ?></td>
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
