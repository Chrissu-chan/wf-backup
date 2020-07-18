<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{nama}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{npwp}}</td>
        <td><?= $model->npwp ?></td>
    </tr>
    <tr>
        <td class="field">{{telepon}}</td>
        <td><?= $model->telepon ?></td>
    </tr>
    <tr>
        <td class="field">{{alamat}}</td>
        <td><?= nl2br($model->alamat) ?></td>
    </tr>
</table>