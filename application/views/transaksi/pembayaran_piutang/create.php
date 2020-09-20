<?php $this->template->section('content') ?>
<?= $this->form->open_multipart($this->route->name('transaksi.pembayaran_piutang.store'), 'id="frm-create"') ?>
<h1 class="page-header">
    {{pembayaran_piutang}}
</h1>
<?php $this->template->view('layouts/partials/message') ?>
<div class="row">
    <div class="col-sm-5">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">{{detail_piutang}}</h4>
            </div>
            <div class="panel-body">
		        <div class="row">
				    <div class="col-sm-12">
				        <div class="form-group">
				            <label>{{no_piutang}}</label>
				            <?= $this->form->select('id_piutang', lists($this->piutang_m->scope('piutang')->get(), 'id', 'no_piutang', $this->localization->lang('pilih_piutang')), ($id_piutang) ? $id_piutang : '', 'id="id_piutang" class="form-control" data-input-type="select2" onchange="find_piutang()" style="width:100%"') ?>
				        </div>
				    </div>
				</div>
				<div class="row">
				    <div class="col-sm-12">
				        <div class="form-group">
				        	<label>{{jumlah_piutang}}</label>
				            <div class="input-group">
				                <span class="input-group-addon">{{currency}}</span>
				                <?= $this->form->text('jumlah_piutang', null, 'id="jumlah_piutang" class="form-control text-right" data-input-type="number-format" readonly') ?>
				            </div>
				        </div>
				    </div>
				</div>
				<div class="row">
				    <div class="col-sm-6">
				        <div class="form-group">
				            <label>{{telah_bayar}}</label>
				            <div class="input-group">
				                <span class="input-group-addon">{{currency}}</span>
				                <?= $this->form->text('telah_bayar', null, 'id="telah_bayar" class="form-control text-right" data-input-type="number-format" readonly') ?>
				                <?= $this->form->hidden('telah_bayar_hide', null, 'id="telah_bayar_hide"') ?>
				            </div>
				        </div>
				    </div>
				    <div class="col-sm-6">
				        <div class="form-group">
				            <label>{{sisa_piutang}}</label>
				            <div class="input-group">
				                <span class="input-group-addon">{{currency}}</span>
				                <?= $this->form->text('sisa_piutang', null, 'id="sisa_piutang" class="form-control text-right" data-input-type="number-format" readonly') ?>
				                <?= $this->form->hidden('sisa_piutang_hide', null, 'id="sisa_piutang_hide"') ?>
				            </div>
				        </div>
				    </div>
				</div>
				<div class="row">
				    <div class="col-md-6">
				        <div class="form-group">
				            <label>{{no_referensi}}</label>
				            <?= $this->form->text('no_ref', null, 'id="no_ref" class="form-control" disabled') ?>
				        </div>
				    </div>
				    <div class="col-md-6">
				        <div class="form-group">
				            <label>{{nama}}</label>
				            <?= $this->form->text('nama', null, 'id="nama" class="form-control" disabled') ?>
				        </div>
				    </div>
				</div>
				<div class="row">
				    <div class="col-md-6">
				        <div class="form-group">
				            <label>{{tanggal_piutang}}</label>
				            <div class="input-group">
				                <?= $this->form->text('tanggal_piutang', null, 'id="tanggal_piutang" class="form-control" readonly') ?>
				                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-6">
				        <div class="form-group">
				            <label>{{tanggal_jatuh_tempo}}</label>
				            <div class="input-group">
				                <?= $this->form->text('tanggal_jatuh_tempo', null, 'id="tanggal_jatuh_tempo" class="form-control" readonly') ?>
				                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				            </div>
				        </div>
				    </div>
				</div>
            </div>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">{{pembayaran_piutang}}</h4>
            </div>
            <div class="panel-body">
            	<div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{tanggal_bayar}}</label>
                            <div class="input-group">
                                <?= $this->form->date('tanggal_bayar', date('d-m-Y'), 'id="tanggal_bayar" class="form-control" data-input-type="dateinput"') ?>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{jumlah_bayar}}</label>
                            <div class="input-group">
                                <span class="input-group-addon">{{currency}}</span>
                                <?= $this->form->text('jumlah_bayar', null, 'id="jumlah_bayar" class="form-control text-right" data-input-type="number-format" onkeyup="count_sisa_piutang()"') ?>
                            </div>
                        </div>
                    </div>
                </div>
            	<div class="row">
            		<div class="col-md-12">
						<div class="form-group">
						    <label>{{dibayarkan_dari}}</label>
						    <?= $this->form->select('dibayarkan_dari', lists($this->kas_bank_m->view('kas_bank')->get(), 'id', function($model) {
						            if($model->jenis_kas_bank == 'bank') {
						                return 'Bank : ' . $model->nomor_rekening . ' a/n ' . $model->nama . '  (' . $model->bank . ')';
						            } else {
						                return 'Kas : ' . $model->nama;
						            }
						        }), null, 'id="dibayarkan_dari" class="form-control"')
						    ?>
						</div>
						<div class="form-group">
						    <label>{{no_ref_pembayaran}}</label>
						    <?= $this->form->text('no_ref_pembayaran', null, 'id="no_ref_pembayaran" class="form-control"') ?>
						</div>
						<div class="form-group">
						    <label>{{keterangan}}</label>
						    <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
						</div>
						<div class="form-group">
						    <label>{{file}}</label>
						    <div class="input-group input-group-sm">
						        <?= $this->form->text('file', null, 'id="file" class="form-control"  onclick="choose_file()" readonly') ?>
						        <?= $this->form->upload('file_path', null, 'id="file_path" onchange="select_file()" style="display: none;"') ?>
						        <span class="input-group-btn">
						            <button type="button" class="btn btn-danger" onclick="delete_file()"><i class="fa fa-trash"></i></button>
						            <button type="button" class="btn btn-default" onclick="choose_file()">...</button>
						        </span>
						    </div>
						</div>
						<div class="form-group">
						    <button type="submit" class="btn btn-success">{{bayar}}</button>
						    <?= $this->action->link('cancel', $this->route->name('transaksi.pembayaran_piutang'), 'class="btn btn-default"') ?>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?= $this->form->close() ?>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
		$('#frm-create').buildForm();
		find_piutang();
    });

    $('#frm-create').buildForm();

    function find_piutang() {
        var id = $('#id_piutang').val();
        if (id) {
	        $.ajax({
		        url: '<?= $this->route->name('transaksi.piutang.find_json') ?>/'+id,
		        success: function(response) {
			        $('#jumlah_piutang').val(response.data.jumlah_piutang);
			        $('#telah_bayar').val(response.data.jumlah_bayar);
			        $('#sisa_piutang').val(response.data.sisa_piutang);
			        $('#sisa_piutang_hide').val(response.data.sisa_piutang);
			        $('#nama').val(response.data.nama);
			        $('#no_ref').val(response.data.no_ref);
			        $('#tanggal_piutang').val(response.data.tanggal_piutang);
			        $('#tanggal_jatuh_tempo').val(response.data.tanggal_jatuh_tempo);
		        }
	        });
        } else {
	        $('#jumlah_piutang').val('');
	        $('#telah_bayar').val('');
	        $('#sisa_piutang').val('');
	        $('#sisa_piutang_hide').val('');
	        $('#nama').val('');
	        $('#no_ref').val('');
	        $('#tanggal_piutang').val('');
	        $('#tanggal_jatuh_tempo').val('');
        }
    }

    function choose_file() {
        $('#file_path').click();
    }

    function select_file() {
        $('#file').val($('#file_path').val().split('\\').pop());
    }

    function delete_file() {
        $('#file_path').val('');
        $('#file').val('');
    }

    function count_sisa_piutang() {
        var jumlah_bayar = $('#jumlah_bayar').val();
    	var jumlah_piutang = $('#jumlah_piutang').val();
    	var sisa_piutang = $('#sisa_piutang').val();
        if(toFloat(sisa_piutang) < 0 || toFloat(jumlah_bayar) > jumlah_piutang) {
            $.growl.error({message: 'Jumlah bayar melebihi sisa piutang.'});
            $('#jumlah_bayar').val(sisa_piutang);
	        $('#sisa_piutang_hide').val(toFloat(sisa_piutang)-toFloat(sisa_piutang));
    	} else {
	        $('#sisa_piutang_hide').val(toFloat(sisa_piutang)-toFloat(jumlah_bayar));
    	}
    }

</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>