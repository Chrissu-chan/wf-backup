<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{kode_member}}</td>
        <td><?= $model->id_cabang ?></td>
    </tr>
    <tr>
        <td class="field">{{data_jenis_member}}</td>
        <td><?= $model->kode ?></td>
    </tr>
    <tr>
        <td class="field">{{nama_member}}</td>
        <td><?= $model->id_jenis_member ?></td>
    </tr>
    <tr>
        <td class="field">{{telepon}}</td>
        <td><?= $model->id_pelanggan ?></td>
    </tr>
    <tr>
        <td class="field">{{hp}}</td>
        <td><?= $model->tanggal_daftar ?></td>
    </tr>
    <tr>
        <td class="field">{{alamat}}</td>
        <td><?= $model->tanggal_expired ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_daftar}}</td>
        <td><?= $model->created_by ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_expired}}</td>
        <td><?= $model->created_at ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_expired_setelah_perpanjangan}}</td>
        <td><?= $model->updated_by ?></td>
    </tr>
</table>