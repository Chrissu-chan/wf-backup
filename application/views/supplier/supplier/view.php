<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{kategori_supplier}}</td>
        <td><?= $model->kategori_supplier ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_supplier}}</td>
        <td><?= $model->jenis_supplier ?></td>
    </tr>
    <tr>
        <td class="field">{{supplier}}</td>
        <td><?= $model->supplier ?></td>
    </tr>
    <tr>
        <td class="field">{{nama_pemilik}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_kelamin}}</td>
        <td><?= $this->supplier_m->enum('jenis_kelamin', $model->jenis_kelamin) ?></td>
    </tr>
    <tr>
        <td class="field">{{telepon}}</td>
        <td><?= $model->telepon ?></td>
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
        <td class="field">{{bank}}</td>
    <td><?= $model->bank ?></td>
    </tr>
    <tr>
        <td class="field">{{no_rekening}}</td>
        <td><?= $model->no_rekening ?></td>
    </tr>
    <tr>
        <td class="field">{{nama_rekening}}</td>
        <td><?= $model->nama_rekening ?></td>
    </tr>
</table>