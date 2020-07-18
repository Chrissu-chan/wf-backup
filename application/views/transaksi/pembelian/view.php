<div class="row">
	<div class="col-md-6">
		<table width="100%" class="table table-profile">
			<tr>
				<td class="field">{{supplier}}</td>
				<td><?= $model->supplier ?></td>
			</tr>
			<tr>
				<td class="field">{{no_pembelian}}</td>
				<td><?= $model->no_pembelian ?></td>
			</tr>
			<tr>
				<td class="field">{{tanggal}}</td>
				<td><?= $this->localization->human_date($model->tanggal) ?></td>
			</tr>
			<tr>
				<td class="field">{{petugas}}</td>
				<td><?= $model->created_by ?></td>
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

<h4>{{pembelian_barang}}</h4>
<table width="100%" class="table table-bordered table-responsive table-condensed">
    <thead>
        <tr>
            <th>{{barang}}</th>
            <th>{{satuan}}</th>
            <th class="text-center">{{jumlah}}</th>
            <th class="text-right">{{harga}}</th>
            <th class="text-right">{{harga}}+{{ppn}}</th>
            <th class="text-center">{{diskon}}</th>
            <th class="text-right">{{potongan}}</th>
            <th class="text-right">{{total}}</th>
            <th>{{expired}}</th>
            <th>{{batch_number}}</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->pembelian_barang as $pembelian_barang) { ?>
            <tr>
                <td><?= $pembelian_barang->kode_barang ?> - <?= $pembelian_barang->nama_barang ?></td>
                <td><?= $pembelian_barang->satuan ?></td>
                <td class="text-center"><?= $this->localization->number($pembelian_barang->jumlah) ?></td>
                <td class="text-right"><?= $this->localization->number($pembelian_barang->harga) ?></td>
	            <td class="text-right"><?= $this->localization->number($pembelian_barang->subtotal) ?></td>
                <td class="text-center"><?= $this->localization->number($pembelian_barang->diskon_persen) ?>%</td>
                <td class="text-right"><?= $this->localization->number($pembelian_barang->potongan) ?></td>
                <td class="text-right"><?= $this->localization->number($pembelian_barang->total, 2) ?></td>
                <td><?= $this->localization->human_date($pembelian_barang->expired) ?></td>
                <td><?= $pembelian_barang->batch_number ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">{{total}}</td>
            <td><?= $this->localization->number($model->total, 2) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>

<h4>{{pembayaran}}</h4>
<div class="row">
    <div class="col-md-6">
        <table width="100%" class="table table-profile">
            <tr>
                <td class="field">{{metode_pembayaran}}</td>
                <td><?= $this->pembelian_m->enum('metode_pembayaran', $model->metode_pembayaran) ?></td>
            </tr>
            <?php if ($model->metode_pembayaran == 'utang') { ?>
                <tr>
                    <td class="field">{{jatuh_tempo}}</td>
                    <td><?= $this->localization->human_date($model->jatuh_tempo) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-md-6">
        <table width="100%" class="table table-profile">
            <tr>
                <td class="field">{{kas_bank}}</td>
                <td><?= $model->kas_bank ?></td>
            </tr>
            <tr>
                <td class="field">{{no_ref}}</td>
                <td><?= $model->no_ref ?></td>
            </tr>
        </table>
    </div>
</div>
