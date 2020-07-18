<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{member}}
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <?= $this->form->open($this->route->name('member.pendaftaran.store'), 'id="frm-create"') ?>
    <?= $this->form->hidden('id_cabang', $this->session->userdata('cabang')->id) ?>
    <div class="row">
        <div class="col-sm-7">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">{{form_identitas_diri}}</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{pelanggan}}</label>
                            <?= $this->form->select('id_pelanggan', lists($this->pelanggan_m->get(), 'id', 'nama', $this->localization->lang('pelanggan_baru')), null, 'id="id_pelanggan" class="form-control" data-input-type="select2" onchange="find_pelanggan()"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{kode_member}}</label>
                                <?= $this->form->text('kode', null, 'id="kode" class="form-control"') ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{jenis_member}}</label>
                                <?= $this->form->select('id_jenis_member', lists($this->jenis_member_m->get(), 'id', 'jenis_member'), null, 'id="id_jenis_member" onchange="find_jenis_member()" class="form-control"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{tanggal_daftar}}</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <?= $this->form->text('tanggal_daftar', date('d-m-Y'), 'id="tanggal_daftar" class="form-control" readonly') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{tanggal_expired}}</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <?= $this->form->text('tanggal_expired', date('d-m-Y', strtotime('+150 days')), 'id="tanggal_expired" class="form-control" readonly') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{nama_pelanggan}}</label>
                                <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{jenis_kelamin}}</label>
                                <?= $this->form->select('jenis_kelamin', $this->member_m->enum('jenis_kelamin'), null, 'id="jenis_kelamin" class="form-control"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{jenis_identitas}}</label>
                                <?= $this->form->select('id_jenis_identitas', lists($this->jenis_identitas_m->get(), 'id', 'jenis_identitas'), null, 'id="id_jenis_identitas" class="form-control"') ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{no_identitas}}</label>
                                <?= $this->form->text('no_identitas', null, 'id="no_identitas" class="form-control"') ?>
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{alamat}}</label>
                                <textarea class="form-control" id="alamat" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">{{form_pembayaran}}</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{biaya}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{currency}}</span>
                                    <?= $this->form->text('biaya', null, 'id="biaya" class="form-control text-right" data-input-type="number-format" readonly') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{diskon}}</label>
                                <div class="input-group">
                                    <?= $this->form->text('diskon_persen', null, 'id="diskon_persen" class="form-control" onkeyup="set_diskon();set_total();set_kembali()"') ?>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{currency}}</span>
                                    <?= $this->form->text('diskon', null, 'id="diskon" class="form-control text-right" data-input-type="number-format" onkeyup="set_diskon_persen();set_total();set_kembali()"') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{ppn}}</label>
                                <div class="input-group">
                                    <?= $this->form->text('ppn_persen', null, 'id="ppn_persen" class="form-control" data-input-type="number-format" readonly') ?>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{currency}}</span>
                                    <?= $this->form->text('ppn', null, 'id="ppn" class="form-control text-right" data-input-type="number-format" readonly') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{total}}</label>
                        <div class="input-group">
                            <span class="input-group-addon">{{currency}}</span>
                            <?= $this->form->text('total', null, 'id="total" class="form-control text-right" data-input-type="number-format" readonly') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{cara_bayar}}</label>
                        <?= $this->form->select('metode_pembayaran', $this->kas_bank_m->enum('kas_bank'), null, 'onchange="cara_bayar()" id="jenis_kas_bank" class="form-control"') ?>
                    </div>
                    <!--If Bayar Kas-->
                    <div class="row" id="cara_bayar_kas_bank">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{bayar}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{currency}}</span>
                                    <?= $this->form->text('bayar', null, 'id="bayar" class="form-control text-right" data-input-type="number-format" onkeyup="set_kembali()"') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>{{kembali}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{currency}}</span>
                                    <?= $this->form->text('kembali', null, 'id="kembali" class="form-control text-right" data-input-type="number-format" readonly') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--If Bayar Bank-->
                    <div class="row cara_bayar_bank">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>{{bank}}</label>
                                <?= $this->form->select('id_kas_bank', lists($this->kas_bank_m->view('bank')->scope('bank')->get(), 'id', function($model) {
                                    return $model->nomor_rekening . ' a/n ' . $model->nama . '  (' . $model->bank . ')';
                                }, $this->localization->lang('pilih_bank')), null, 'id="bank" class="form-control"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group cara_bayar_bank">
                        <label>{{no_referensi_pembayaran}}</label>
                        <div class="input-group">
                            <span class="input-group-addon">No</span>
                            <?= $this->form->text('no_ref_pembayaran', null, 'id="no_ref_pembayaran" class="form-control text-right"') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-left">
                <button type="submit" class="btn btn-success">{{store_all_data}}</button>
            </div>
        </div>
    </div>
    <?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>

    $(function() {
        cara_bayar();
        find_jenis_member();
    })

    function set_diskon() {
        var biaya = $('#biaya').val();
        var diskon_persen = $('#diskon_persen').val();
        if(biaya) {
            $('#diskon').val((diskon_persen/100)*biaya);
        }
        set_ppn();
    }

    function set_diskon_persen() {
        var biaya = $('#biaya').val();
        var diskon = $('#diskon').val();
        if(biaya) {
            $('#diskon_persen').val(parseInt((toFloat(diskon)/toFloat(biaya))*100));
        }
        set_ppn();
    }

    function set_ppn() {
        var biaya = $('#biaya').val();
        var diskon = $('#diskon').val();
        var ppn_persen = $('#ppn_persen').val();
        var temp = toFloat(biaya)-toFloat(diskon);
        $('#ppn').val(temp*(ppn_persen/100));
    }

    function set_total() {
        var biaya = $('#biaya').val();
        var diskon = $('#diskon').val();
        var ppn = $('#ppn').val();
        ppn = (ppn) ? ppn : 0;
        diskon = (diskon) ? diskon : 0;
        if(diskon) {
            $('#total').val(toFloat(biaya)-toFloat(diskon)+toFloat(ppn));
        }
    }

    function set_kembali() {
        var bayar = $('#bayar').val();
        var total = $('#total').val();
        if(bayar && total) {
            $('#kembali').val(bayar-total);
        }
    }

    function find_pelanggan() {
        id = $('#id_pelanggan').val();
        if(id) {
            $.ajax({
                url: '<?= $this->route->name('pelanggan.pelanggan.json') ?>/'+id,
                success: function(response) {
                    if (response.success) {
                        $('#nama').val(response.data.nama).prop('readonly', true);
                        $('#id_jenis_identitas').val(response.data.id_jenis_identitas).change().prop('disabled', true);
                        $('#no_identitas').val(response.data.no_identitas).prop('readonly', true);
                        $('#jenis_kelamin').val(response.data.jenis_kelamin).change().prop('disabled', true);
                        $('#telepon').val(response.data.telepon).prop('readonly', true);
                        $('#hp').val(response.data.hp).prop('readonly', true);
                        $('#id_kota').val(response.data.id_kota).change().prop('readonly', true);
                        $('#alamat').val(response.data.alamat).prop('readonly', true);
                    }
                }
            });
        } else {
            $('#nama').val('').prop('readonly', false);
            $('#id_jenis_identitas').val('').change().prop('disabled', false);
            $('#no_identitas').val('').prop('readonly', false);
            $('#jenis_kelamin').val('').change().prop('readonly', false);
            $('#telepon').val('').prop('readonly', false);
            $('#hp').val('').prop('readonly', false);
            $('#id_kota').val('').change().prop('readonly', false);
            $('#alamat').val('').prop('readonly', false);
        }
    }

    function cara_bayar() {
        var cara_bayar = $('#jenis_kas_bank').val();
        if(cara_bayar == 'bank') {
            $('#cara_bayar_kas_bank').css('display', 'none');
            $('.cara_bayar_bank').removeAttr('style');
        } else {
            $('.cara_bayar_bank').css('display', 'none');
            $('#cara_bayar_kas_bank').removeAttr('style');
        }
        $('#no_ref_pembayaran').val('').change();
        $('#bayar').val('');
        $('#kembali').val('');
    }

    function find_jenis_member() {
        id = $('#id_jenis_member').val();
        $.ajax({
            url: '<?= $this->route->name('member.jenis_member.json') ?>/'+id,
            success: function(response) {
                if (response.success) {
                   $('#biaya').val(response.data.biaya);
                   $('#ppn').val(response.data.ppn);
                   $('#ppn_persen').val(response.data.ppn_persen);
                   $('#tanggal_expired').val(moment(moment().format("DD-MM-YYYY"), "DD-MM-YYYY").add('days', response.data.masa_aktif).format('DD-MM-YYYY'));
                }
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>