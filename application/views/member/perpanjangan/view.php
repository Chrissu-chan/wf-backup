<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{kode}}</td>
        <td><?= $model->kode ?></td>
    </tr>
    <tr>
        <td class="field">{{nama}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_identitas}}</td>
        <td><?= $model->jenis_identitas ?></td>
    </tr>
    <tr>
        <td class="field">{{no_identitas}}</td>
        <td><?= $model->no_identitas ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_kelamin}}</td>
        <td><?= $this->member_m->enum('jenis_kelamin', $model->jenis_kelamin) ?></td>
    </tr>
    <tr>
        <td class="field">{{telepon}}</td>
        <td><?= $model->telepon ?></td>
    </tr>
    <tr>
        <td class="field">{{hp}}</td>
        <td><?= $model->hp ?></td>
    </tr>
    <tr>
        <td class="field">{{kota}}</td>
        <td><?= $model->kota ?></td>
    </tr>
    <tr>
        <td class="field">{{alamat}}</td>
        <td><?= nl2br($model->alamat) ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_member}}</td>
        <td><?= $model->jenis_member ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_daftar}}</td>
        <td><?= $this->localization->human_date($model->tanggal_daftar) ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_expired}}</td>
        <td><?= $this->localization->human_date($model->tanggal_expired) ?></td>
    </tr>
</table>