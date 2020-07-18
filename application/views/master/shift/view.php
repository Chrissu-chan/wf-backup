<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{shift}}</td>
        <td><?= $model->shift ?></td>
    </tr>
    <tr>
        <td class="field">{{jumlah_shift}}</td>
        <td><?= $model->jumlah_shift ?></td>
    </tr>
    <tr>
        <td class="field">Waktu</td>
        <td>
            <?php foreach ($model->waktu as $waktu) { ?>
                <p><?= $waktu->shift_waktu ?> (<?= $waktu->jam_mulai ?> - <?= $waktu->jam_selesai ?>)</p>
            <?php } ?>
        </td>
    </tr>
</table>