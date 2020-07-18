<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{no_produksi}}</td>
        <td><?= $model->no_produksi ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_produksi}}</td>
        <td><?= $this->localization->human_date($model->tanggal_produksi) ?></td>
    </tr>
    <tr>
        <td class="field">{{barang}}</td>
        <td><?= $model->kode_barang.' - '.$model->nama_barang ?></td>
    </tr>
    <tr>
        <td class="field">{{nama}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{satuan}}</td>
        <td><?= $model->satuan ?></td>
    </tr>
    <tr>
        <td class="field">{{jumlah}}</td>
        <td><?= $model->jumlah ?></td>
    </tr>
    <tr>
        <td class="field">{{produksi_bahan_baku}}</td>
        <td>
            <?php if ($model->produksi_bahan_baku) { ?>
                <?php foreach ($model->produksi_bahan_baku as $produksi_bahan_baku) { ?>
                    <?= $produksi_bahan_baku->jumlah.' '.$produksi_bahan_baku->satuan.' '.$produksi_bahan_baku->nama_barang.'('.$produksi_bahan_baku->kode_barang.')<br>' ?>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td class="field">{{total_biaya_bahan_baku}}</td>
        <td><?= $this->localization->number($model->total_bahan_baku) ?></td>
    </tr>
    <tr>
        <td class="field">{{total_biaya_lainnya}}</td>
        <td><?= $this->localization->number($model->total_biaya_lainnya) ?></td>
    </tr>
    <tr>
        <td class="field">{{total_biaya_produksi}}</td>
        <td><?= $this->localization->number($model->total_biaya_produksi) ?></td>
    </tr>
    <tr>
        <td class="field">{{hpp}}</td>
        <td><?= $this->localization->number($model->hpp) ?></td>
    </tr>
</table>