<div class="row">
	<div class="col-md-6">
		<table width="100%" class="table table-profile">
			<tr>
				<td class="field">{{pelanggan}}</td>
				<td><?= $model->pelanggan ?></td>
			</tr>
			<tr>
				<td class="field">{{no_penjualan}}</td>
				<td><?= $model->no_penjualan ?></td>
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
					<td><?= $this->penjualan_m->enum('jenis_batal', $model->jenis_batal) ?></td>
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

<h4>{{penjualan_produk}}</h4>
<table width="100%" class="table table-bordered table-responsive table-condensed">
    <thead>
        <tr>
            <th>{{produk}}</th>
            <th>{{satuan}}</th>
            <th class="text-center">{{jumlah}}</th>
            <th class="text-right">{{harga}}</th>
            <th class="text-center">{{diskon}}</th>
            <th class="text-right">{{potongan}}</th>
            <th class="text-right">{{subtotal}}</th>
            <th class="text-center">{{ppn}}</th>
            <th class="text-right">{{tuslah}}</th>
            <th class="text-right">{{total}}</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->penjualan_produk as $penjualan_produk) { ?>
            <tr>
                <td><?= $penjualan_produk->kode_produk ?> - <?= $penjualan_produk->nama_produk ?></td>
                <td><?= $penjualan_produk->satuan ?></td>
                <td class="text-center"><?= $this->localization->number($penjualan_produk->jumlah) ?></td>
                <td class="text-right"><?= $this->localization->number($penjualan_produk->harga) ?></td>
                <td class="text-center"><?= $this->localization->number($penjualan_produk->diskon_persen) ?>%</td>
                <td class="text-right"><?= $this->localization->number($penjualan_produk->potongan) ?></td>
                <td class="text-right"><?= $this->localization->number($penjualan_produk->subtotal) ?></td>
                <td class="text-center"><?= $this->localization->number($penjualan_produk->ppn_persen) ?>%</td>
                <td class="text-right"><?= $this->localization->number($penjualan_produk->tuslah) ?></td>
                <td class="text-right"><?= $this->localization->number($penjualan_produk->total, 2) ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9">{{total}}</td>
            <td class="text-right"><?= $this->localization->number($model->total, 2) ?></td>
        </tr>
    </tfoot>
</table>

<h4>{{pembayaran}}</h4>
<div class="row">
    <div class="col-md-6">
        <table width="100%" class="table table-profile">
            <tr>
                <td class="field">{{metode_pembayaran}}</td>
                <td><?= $this->penjualan_m->enum('metode_pembayaran', $model->metode_pembayaran) ?></td>
            </tr>
            <?php if ($model->metode_pembayaran == 'utang') { ?>
                <tr>
                    <td class="field">{{jatuh_tempo}}</td>
                    <td><?= $this->localization->human_date($model->jatuh_tempo) ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td class="field">{{total_bayar}}</td>
                    <td class="text-right"><?= $this->localization->number($model->bayar) ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="field">{{kembali}}</td>
                    <td class="text-right"><?= $this->localization->number($model->kembali) ?></td>
                    <td>&nbsp;</td>
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
