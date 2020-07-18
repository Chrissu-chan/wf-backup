<div id="frm-message"></div>
<?= $this->form->hidden('id_cabang', $this->session->userdata('cabang')->id, 'id="id_cabang"') ?>
<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#identitas_diri" role="tab" data-toggle="tab">{{identitas_diri}}</a></li>
        <li><a href="#riwayat_penyakit" role="tab" data-toggle="tab">{{riwayat_penyakit}}</a></li>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="identitas_diri">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label>{{nama}}</label>
                        <?= $this->form->hidden('id', null, 'id="id_pasien"') ?>
                        <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>{{jenis_identitas}}</label>
                        <?= $this->form->select('id_jenis_identitas', lists($this->jenis_identitas_m->get(), 'id', 'jenis_identitas'), null, 'id="id_jenis_identitas" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{no_identitas}}</label>
                        <?= $this->form->text('no_identitas', null, 'id="no_identitas" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{jenis_kelamin}}</label>
                        <?= $this->form->select('jenis_kelamin', $this->pasien_m->enum('jenis_kelamin'), null, 'id="jenis_kelamin" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{tempat_lahir}}</label>
                        <?= $this->form->select('tempat_lahir', lists($this->kota_m->get(), 'id', 'kota'), null, 'id="tempat_lahir" class="form-control" data-input-type="select2" style="width:100%"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{tanggal_lahir}}</label>
                        <?= $this->form->date('tanggal_lahir', date('Y-m-d'), 'id="tanggal_lahir" class="form-control" data-input-type="datepicker"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{agama}}</label>
                        <?= $this->form->select('agama', $this->pasien_m->enum('agama'), null, 'id="agama" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{pendidikan}}</label>
                        <?= $this->form->select('pendidikan', $this->pasien_m->enum('pendidikan'), null, 'id="pendidikan" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{telepon}}</label>
                        <?= $this->form->text('telepon', null, 'id="telepon" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{hp}}</label>
                        <?= $this->form->text('hp', null, 'id="hp" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{status_pernikahan}}</label>
                        <?= $this->form->select('status_pernikahan', $this->pasien_m->enum('status_pernikahan'), null, 'id="status_pernikahan" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{pekerjaan}}</label>
                        <?= $this->form->text('pekerjaan', null, 'id="pekerjaan" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{kota}}</label>
                        <?= $this->form->select('id_kota', lists($this->kota_m->get(), 'id', 'kota', '- Pilih Kota -'), null, 'id="id_kota" class="form-control" data-input-type="select2" style="width:100%"') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>{{alamat}}</label>
                        <?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="riwayat_penyakit">
            <div class="form-group">
                <label>{{riwayat_alergi}}</label>
                <select name="id_alergi[]" id="id_alergi" style="width: 100%" multiple="multiple"></select>
            </div>
            <div class="form-group">
                <label>{{riwayat_penyakit}}</label>
                <?= $this->form->select('id_penyakit[]', null, null, 'id="id_penyakit" class="form-control" style="width:100%" multiple') ?>
            </div>
            <div class="form-group">
                <label>{{riwayat_biologis}}</label>
                <?= $this->form->select('id_penyakit_biologis[]', null, null, 'id="id_penyakit_biologis" class="form-control" style="width:100%" multiple') ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#id_alergi').select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?= $this->route->name('master.penyakit.json') ?>',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.penyakit,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        $.ajax({
            type: 'GET',
            url: '<?= $this->route->name('rekam_medis.pasien.find_pasien_alergi_json') ?>?id_pasien=' + $('#id_pasien').val()
        }).then(function (data) {
            var option;
            $.each(data.data, function(index, value) {
                option = new Option(value.alergi, value.id, true, true);
                $('#id_alergi').append(option).trigger('change');
            });
        });

        $('#id_penyakit').select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?= $this->route->name('master.penyakit.json') ?>',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.penyakit,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        $.ajax({
            type: 'GET',
            url: '<?= $this->route->name('rekam_medis.pasien.find_pasien_penyakit_json') ?>?id_pasien=' + $('#id_pasien').val()
        }).then(function (data) {
            var option;
            $.each(data.data, function(index, value) {
                option = new Option(value.penyakit, value.id, true, true);
                $('#id_penyakit').append(option).trigger('change');
            });
        });

        $('#id_penyakit_biologis').select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?= $this->route->name('master.penyakit.json') ?>',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.penyakit,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        $.ajax({
            type: 'GET',
            url: '<?= $this->route->name('rekam_medis.pasien.find_pasien_penyakit_biologis_json') ?>?id_pasien=' + $('#id_pasien').val()
        }).then(function (data) {
            var option;
            $.each(data.data, function(index, value) {
                option = new Option(value.penyakit_biologis, value.id, true, true);
                $('#id_penyakit_biologis').append(option).trigger('change');
            });
        });
    });
</script>