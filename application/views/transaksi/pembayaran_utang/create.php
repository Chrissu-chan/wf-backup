<?php $this->template->section('content') ?>
<?= $this->form->open_multipart($this->route->name('transaksi.pembayaran_utang.store'), 'id="frm-create"') ?>
<h1 class="page-header">
    {{pembayaran_utang}}
</h1>
<?php $this->template->view('layouts/partials/message') ?>
<div class="row">
    <div class="col-sm-5">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">{{detail_utang}}</h4>
            </div>
            <div class="panel-body">
		        <div class="row">
				    <div class="col-sm-12">
				        <div class="form-group">
				            <label>{{no_utang}}</label>
				            <?= $this->form->select('id_utang', lists($this->utang_m->scope('utang')->get(), 'id', 'no_utang', $this->localization->lang('pilih_utang')), ($id_utang) ? $id_utang : '', 'id="id_utang" class="form-control" data-input-type="select2" onchange="find_utang()" style="width:100%"') ?>
				        </div>
				    </div>
				</div>
				<div class="row">
				    <div class="col-sm-12">
				        <div class="form-group">
				        	<label>{{jumlah_utang}}</label>
				            <div class="input-group">
				                <span class="input-group-addon">{{currency}}</span>
				                <?= $this->form->text('jumlah_utang', null, 'id="jumlah_utang" class="form-control text-right" data-input-type="number-format" readonly') ?>
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
				            <label>{{sisa_utang}}</label>
				            <div class="input-group">
				                <span class="input-group-addon">{{currency}}</span>
				                <?= $this->form->text('sisa_utang', null, 'id="sisa_utang" class="form-control text-right" data-input-type="number-format" readonly') ?>
				                <?= $this->form->hidden('sisa_utang_hide', null, 'id="sisa_utang_hide"') ?>
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
				            <label>{{tanggal_utang}}</label>
				            <div class="input-group">
				                <?= $this->form->date('tanggal_utang', null, 'id="tanggal_utang" class="form-control" readonly') ?>
				                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				            </div>
				        </div>
				    </div>
				    <div class="col-md-6">
				        <div class="form-group">
				            <label>{{tanggal_jatuh_tempo}}</label>
				            <div class="input-group">
				                <?= $this->form->date('tanggal_jatuh_tempo', null, 'id="tanggal_jatuh_tempo" class="form-control" readonly') ?>
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
                <h4 class="panel-title">{{pembayaran_utang}}</h4>
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
                                <?= $this->form->text('jumlah_bayar', null, 'id="jumlah_bayar" class="form-control text-right" data-input-type="number-format" onkeyup="count_sisa_utang()"') ?>
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
						    <?= $this->action->link('cancel', $this->route->name('transaksi.pembayaran_utang'), 'class="btn btn-default"') ?>
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
		find_utang();
    });

    $('#frm-create').buildForm();

    function find_utang() {
        var id = $('#id_utang').val();
        if (id) {
	        $.ajax({
		        url: '<?= $this->route->name('transaksi.utang.find_json') ?>/'+id,
		        success: function(response) {
			        $('#jumlah_utang').val(response.data.jumlah_utang);
			        $('#telah_bayar').val(response.data.jumlah_bayar);
			        $('#sisa_utang').val(response.data.sisa_utang);
			        $('#sisa_utang_hide').val(response.data.sisa_utang);
			        $('#nama').val(response.data.nama);
			        $('#no_ref').val(response.data.no_ref);
			        $('#tanggal_utang').val(response.data.tanggal_utang);
			        $('#tanggal_jatuh_tempo').val(response.data.tanggal_jatuh_tempo);
		        }
	        });
        } else {
	        $('#jumlah_utang').val('');
	        $('#telah_bayar').val('');
	        $('#sisa_utang').val('');
	        $('#sisa_utang_hide').val('');
	        $('#nama').val('');
	        $('#no_ref').val('');
	        $('#tanggal_utang').val('');
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

    function count_sisa_utang() {
        var jumlah_bayar = $('#jumlah_bayar').val();
    	var jumlah_utang = $('#jumlah_utang').val();
    	var sisa_utang = $('#sisa_utang').val();
        if(toFloat(sisa_utang) < 0 || toFloat(jumlah_bayar) > jumlah_utang) {
            $.growl.error({message: 'Jumlah bayar melebihi sisa utang.'});
            $('#jumlah_bayar').val(sisa_utang);
	        $('#sisa_utang_hide').val(toFloat(sisa_utang)-toFloat(sisa_utang));
    	} else {
	        $('#sisa_utang_hide').val(toFloat(sisa_utang)-toFloat(jumlah_bayar));
    	}
    }

</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>