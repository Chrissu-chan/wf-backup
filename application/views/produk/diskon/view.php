<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{diskon}}</td>
        <td><?= $model->diskon ?>%</td>
    </tr>
    <tr>
        <td class="field">{{potongan}}</td>
        <td><?= $this->localization->number($model->potongan) ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_mulai}}</td>
        <td><?= $this->localization->human_date($model->tanggal_mulai) ?></td>
    </tr>
    <tr>
        <td class="field">{{tanggal_selesai}}</td>
        <td><?= $this->localization->human_date($model->tanggal_selesai) ?></td>
    </tr>
    <tr>
        <td class="field">{{keterangan}}</td>
        <td><?= $model->keterangan ?></td>
    </tr>
    <tr>
        <td class="field">{{status}}</td>
        <td>
            <?php if ($model->aktif == 0) { ?>
                <label class="label label-danger"><?= $this->diskon_m->enum('aktif', $model->aktif) ?></label>
            <?php } else { ?>
                <label class="label label-success"><?= $this->diskon_m->enum('aktif', $model->aktif) ?></label>
            <?php } ?>
        </td>
    </tr>
</table>

<?php if ($model->diskon_kondisi) { ?>
	<hr>
	<h4>{{kriteria}}</h4>
	<table class="table table-bordered table-condensed">
		<tbody>
		<?php foreach($model->diskon_kondisi as $diskon_kondisi) { ?>
			<tr>
				<td width="150"><?= $this->localization->lang($diskon_kondisi['column']) ?></td>
				<td width="1"><?= $diskon_kondisi['operation'] ?></td>
				<td>
					<?php
						if ($diskon_kondisi['column'] == 'harga') {
							$from = $this->localization->number($diskon_kondisi['from']);
							if ($diskon_kondisi['operation'] == '=' && $diskon_kondisi['to'] > 0) {
								$from .= ' - '.$this->localization->number($diskon_kondisi['to']);
							}
						} else {
							$from = (is_array($diskon_kondisi['from']) ? implode(',', $diskon_kondisi['from']) : $diskon_kondisi['from']);
						}
					?>
					<?= $from ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } ?>