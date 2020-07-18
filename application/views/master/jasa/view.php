<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{jasa}}</td>
        <td><?= $model->jasa ?></td>
    </tr>
    <tr>
        <td class="field">{{kategori_jasa}}</td>
        <td><?= $model->kategori_jasa ?></td>
    </tr>
    <tr>
        <td class="field">{{pemakaian_barang}}</td>
        <td>
            <?php if ($model->pemakaian_barang) { ?>
                <?php foreach ($model->pemakaian_barang as $pemakaian_barang) { ?>
                    <?= $pemakaian_barang->jumlah.' '.$pemakaian_barang->satuan.' '.$pemakaian_barang->nama_barang.'('.$pemakaian_barang->kode_barang.')<br>' ?>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
</table>