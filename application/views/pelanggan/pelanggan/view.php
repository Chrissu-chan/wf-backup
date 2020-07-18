<table width="100%" class="table table-profile">
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
        <td><?= $this->pelanggan_m->enum('jenis_kelamin', $model->jenis_kelamin) ?></td>
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
</table>