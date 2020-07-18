<table width="100%" class="table table-profile">
	<tr>
		<td class="field">{{permanen}}</td>
		<td><?= $this->localization->boolean($model->permanen) ?></td>
	</tr>
    <tr>
        <td class="field">{{perubahan_harga}}</td>
        <td><?= $model->perubahan_harga ?>%</td>
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
                <label class="label label-danger"><?= $this->perubahan_harga_m->enum('aktif', $model->aktif) ?></label>
            <?php } else { ?>
                <label class="label label-success"><?= $this->perubahan_harga_m->enum('aktif', $model->aktif) ?></label>
            <?php } ?>
        </td>
    </tr>
</table>

<?php if ($model->perubahan_harga_kondisi) { ?>
	<hr>
	<h4>{{kriteria}}</h4>
	<table class="table table-bordered table-condensed">
		<tbody>
		<?php foreach($model->perubahan_harga_kondisi as $perubahan_harga_kondisi) { ?>
			<tr>
				<td width="150"><?= $this->localization->lang($perubahan_harga_kondisi['column']) ?></td>
				<td width="1"><?= $perubahan_harga_kondisi['operation'] ?></td>
				<td>
					<?php
						if ($perubahan_harga_kondisi['column'] == 'harga') {
							$from = $this->localization->number($perubahan_harga_kondisi['from']);
							if ($perubahan_harga_kondisi['operation'] == '=' && $perubahan_harga_kondisi['to'] > 0) {
								$from .= ' - '.$this->localization->number($perubahan_harga_kondisi['to']);
							}
						} else {
							$from = (is_array($perubahan_harga_kondisi['from']) ? implode(',', $perubahan_harga_kondisi['from']) : $perubahan_harga_kondisi['from']);
						}
					?>
					<?= $from ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } ?>