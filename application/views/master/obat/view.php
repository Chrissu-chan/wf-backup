<table width="100%" class="table table-profile">
    <tr class="highlight">
        <td colspan="2"><b>{{barang}}</b></td>
    </tr>
    <tr>
        <td class="field">{{kode}}</td>
        <td><?= $model->kode ?></td>
    </tr>
    <tr>
        <td class="field">{{barcode}}</td>
        <td><?= $model->barcode ?></td>
    </tr>
    <tr>
        <td class="field">{{nama}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{kategori_barang}}</td>
        <td><?= $model->kategori_barang ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_barang}}</td>
        <td><?= $model->jenis_barang ?></td>
    </tr>
    <tr>
        <td class="field">{{satuan_barang}}</td>
        <td><?= $model->satuan ?></td>
    </tr>
    <tr>
        <td class="field">{{satuan_beli}}</td>
        <td><?= $model->satuan_beli ?></td>
    </tr>
    <tr class="highlight">
        <td colspan="2"><b>{{obat}}</b></td>
    </tr>
    <tr>
        <td class="field">{{jenis_obat}}</td>
        <td><?= $model->jenis_obat ?></td>
    </tr>
    <tr>
        <td class="field">{{kategori_obat}}</td>
        <td><?= $model->kategori_obat ?></td>
    </tr>
    <tr>
        <td class="field">{{fungsi_obat}}</td>
        <td><?= $model->fungsi_obat ?></td>
    </tr>
    <tr>
        <td class="field">{{kandungan_obat}}</td>
        <td><?= nl2br($model->kandungan_obat) ?></td>
    </tr>
    <tr>
        <td class="field">{{dosis}}</td>
        <td><?= $this->localization->number($model->dosis) ?></td>
    </tr>
    <tr>
        <td class="field">{{minus}}</td>
        <td><?= $this->localization->boolean($model->minus) ?></td>
    </tr>
    <tr>
        <td class="field">{{hna}}</td>
        <td><?= $this->localization->number($model->hpp) ?></td>
    </tr>
	<tr>
		<td class="field">{{ppn_persen}}</td>
		<td><?= $this->localization->number($model->ppn_persen) ?>%</td>
	</tr>
	<tr>
		<td class="field">{{hna}}+{{ppn}}</td>
		<td><?= $this->localization->number($model->hna) ?></td>
	</tr>
    <tr>
        <td class="field">{{diskon_persen}}</td>
        <td><?= $this->localization->number($model->diskon_persen) ?>%</td>
    </tr>
    <tr>
        <td class="field">{{total}}</td>
        <td><?= $this->localization->number($model->total) ?></td>
    </tr>
</table>