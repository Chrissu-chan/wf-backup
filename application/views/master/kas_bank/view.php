<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{nama}}</td>
        <td><?= $model->nama ?></td>
    </tr>
    <tr>
        <td class="field">{{jenis_kas_bank}}</td>
        <td><?= $model->jenis_kas_bank ?></td>
    </tr>
    <tr>
        <td class="field">{{cabang}}</td>
        <td><?= $model->cabang ?></td>
    </tr>
    <?php if ($model->jenis_kas_bank == 'bank'): ?>
        <tr>
            <td class="field">{{bank}}</td>
            <td><?= $model->bank ?></td>
        </tr>
        <tr>
            <td class="field">{{nomor_rekening}}</td>
            <td><?= $model->nomor_rekening ?></td>
        </tr>
        <tr>
            <td class="field">{{nama_rekening}}</td>
            <td><?= $model->nama_rekening ?></td>
        </tr>
    <?php endif ?>
</table>