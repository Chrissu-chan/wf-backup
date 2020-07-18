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
        <td class="field">{{jenis_kelamin}}</td>
        <td><?= $model->id_jenis_member ?></td>
    </tr>
    <tr>
        <td class="field">{{telepon}}</td>
        <td><?= $model->id_pelanggan ?></td>
    </tr>
    <tr>
        <td class="field">{{hp}}</td>
        <td><?= $this->localization->human_date($model->tanggal_daftar) ?></td>
    </tr>
    <tr>
        <td class="field">{{alamat}}</td>
        <td><?= $this->localization->human_date($model->tanggal_expired) ?></td>
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
        <td class="field">{{harga}}</td>
        <td><?= $model->updated_by ?></td>
    </tr>
    <tr>
        <td class="field">{{diskon}}</td>
        <td><?= $model->updated_at ?></td>
    </tr>
    <tr>
        <td class="field">{{potongan}}</td>
        <td><?= $model->updated_by ?></td>
    </tr>
    <tr>
        <td class="field">{{total}}</td>
        <td><?= $model->updated_at ?></td>
    </tr>
</table>