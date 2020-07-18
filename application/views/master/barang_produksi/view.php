<table width="100%" class="table table-profile">
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
        <td class="field">{{bahan_baku}}</td>
        <td>
            <?php if ($model->bahan_baku) { ?>
                <?php foreach ($model->bahan_baku as $bahan_baku) { ?>
                    <?= $bahan_baku->jumlah.' '.$bahan_baku->satuan.' '.$bahan_baku->nama_barang.'('.$bahan_baku->kode_barang.')<br>' ?>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
</table>