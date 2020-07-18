<div class="form-group">
    <label>{{shift}}</label>
    <?= $this->form->text('shift', null, 'class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jumlah_shift}}</label>
    <?= $this->form->text('jumlah_shift', null, 'id="jumlah_shift" class="form-control"') ?>
</div>
<table id="waktu" class="table table-condensed">
    <thead>
        <tr>
            <th>{{keterangan}}</th>
            <th>{{jam_mulai}}</th>
            <th>{{jam_selesai}}</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($model->waktu)) { ?>
            <?php foreach ($model->waktu as $waktu) { ?>
                <tr>
                    <td><?= $this->form->text('waktu['.$waktu->urutan.'][shift_waktu]', $waktu->shift_waktu, 'class="form-control input-sm"') ?></td>
                    <td>
                        <div class="input-group bootstrap-timepicker">
                            <?= $this->form->text('waktu['.$waktu->urutan.'][jam_mulai]', $waktu->jam_mulai, 'class="form-control" data-input-type="timepicker"') ?>
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group bootstrap-timepicker">
                            <?= $this->form->text('waktu['.$waktu->urutan.'][jam_selesai]', $waktu->jam_selesai, 'class="form-control" data-input-type="timepicker" onchange="set_next_shift('.$waktu->urutan.')"') ?>
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>