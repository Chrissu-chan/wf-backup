<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#tab-identitas_diri" role="tab" data-toggle="tab">{{identitas_diri}}</a></li>
        <li><a href="#tab-riwayat_penyakit" role="tab" data-toggle="tab">{{riwayat_penyakit}}</a></li>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="tab-identitas_diri">
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
                    <td><?= $this->pasien_m->enum('jenis_kelamin', $model->jenis_kelamin) ?></td>
                </tr>
                <tr>
                    <td class="field">{{tempat_lahir}}</td>
                    <td><?= $model->tempat_lahir ?></td>
                </tr>
                <tr>
                    <td class="field">{{tanggal_lahir}}</td>
                    <td><?= $this->localization->human_date($model->tanggal_lahir) ?></td>
                </tr>
                <tr>
                    <td class="field">{{agama}}</td>
                    <td><?= $this->pasien_m->enum('agama', $model->agama) ?></td>
                </tr>
                <tr>
                    <td class="field">{{pendidikan}}</td>
                    <td><?= $this->pasien_m->enum('pendidikan', $model->pendidikan) ?></td>
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
                    <td class="field">{{status_pernikahan}}</td>
                    <td><?= $this->pasien_m->enum('status_pernikahan', $model->status_pernikahan) ?></td>
                </tr>
                <tr>
                    <td class="field">{{pekerjaan}}</td>
                    <td><?= $model->pekerjaan ?></td>
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
        </div>
        <div role="tabpanel" class="tab-pane active" id="tab-riwayat_penyakit">
            <table width="100%" class="table table-profile nowrap">
                <tr>
                    <td class="field">{{riwayat_alergi}}</td>
                    <?php if ($model->alergi): ?>
                        <td><?= implode(", ", $model->alergi) ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td class="field">{{riwayat_penyakit}}</td>
                    <?php if ($model->penyakit): ?>
                        <td><?= implode(", ", $model->penyakit) ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td class="field">{{riwayat_penyakit_biologis}}</td>
                    <?php if ($model->penyakit_biologis): ?>
                        <td><?= implode(", ", $model->penyakit_biologis) ?></td>
                    <?php endif ?>
                </tr>
            </table>
        </div>
    </div>
</div>
