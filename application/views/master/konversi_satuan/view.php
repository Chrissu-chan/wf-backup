<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{satuan_utama}}</td>
        <td><?= $model->satuan_asal ?></td>
    </tr>
    <tr>
        <td class="field">{{satuan_konversi}}</td>
        <td><?= $model->satuan_tujuan ?></td>
    </tr>
    <tr>
        <td class="field">{{konversi}}</td>
        <td>1 <?= $model->satuan_asal . ' = ' . $model->konversi . ' ' . $model->satuan_tujuan ?></td>
    </tr>
</table>