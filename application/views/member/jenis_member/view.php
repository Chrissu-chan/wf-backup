<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{jenis_member}}</td>
        <td><?= $model->jenis_member ?></td>
    </tr>
    <tr>
        <td class="field">{{biaya}}</td>
        <td><?= $this->localization->number($model->biaya) ?></td>
    </tr>
    <tr>
        <td class="field">{{ppn}}</td>
        <td><?= $this->localization->number($model->ppn) ?></td>
    </tr>
    <tr>
        <td class="field">{{ppn_persen}}</td>
        <td><?= $model->ppn_persen ?> %</td>
    </tr>
    <tr>
        <td class="field">{{total}}</td>
        <td><?= $this->localization->number($model->total) ?></td>
    </tr>
    <tr>
        <td class="field">{{masa_aktif}}</td>
        <td><?= $model->masa_aktif ?> {{hari}}</td>
    </tr>
</table>