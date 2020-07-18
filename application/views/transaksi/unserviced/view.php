<div class="row">
	<div class="col-md-6">
		<table width="100%" class="table table-profile">
			<tr>
				<td class="field">{{no_servis}}</td>
				<td><?= $model->no_servis ?></td>
			</tr>
			<tr>
				<td class="field">{{tanggal}}</td>
				<td><?= $this->localization->human_date($model->tanggal) ?></td>
			</tr>
			<tr>
				<td class="field">{{created_at}}</td>
				<td><?= $this->localization->human_datetime($model->created_at) ?></td>
			</tr>
		</table>
	</div>
	<div class="col-md-6">
		<?php if ($model->batal) { ?>
			<table width="100%" class="table table-profile">
				<tr>
					<td class="field">{{jenis_batal}}</td>
					<td><?= $this->pembelian_m->enum('jenis_batal', $model->jenis_batal) ?></td>
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

<h4>{{unserviced_detail}}</h4>
<table width="100%" class="table table-bordered table-responsive table-condensed">
    <thead>
        <tr>
            <th>{{barang}}</th>
            <th width="200">{{satuan}}</th>
            <th class="text-center" width="100">{{jumlah}}</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->unserviced_detail as $unserviced_detail) { ?>
            <tr>
                <td><?= $unserviced_detail->nama_barang ?></td>
                <td><?= $unserviced_detail->satuan ?></td>
                <td class="text-center"><?= $this->localization->number($unserviced_detail->jumlah) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
