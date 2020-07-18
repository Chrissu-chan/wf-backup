<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{kode_jenis_transaksi}}</td>
        <td><?= $model->kode_jenis_transaksi ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_transaksi}}</td>
        <td><?= $model->jenis_transaksi ?></td>
    </tr>
    <tr>
        <td class="field">{{tipe}}</td>
        <td><?= $this->jenis_transaksi_m->enum('tipe', $model->tipe) ?></td>
    </tr>
</table>