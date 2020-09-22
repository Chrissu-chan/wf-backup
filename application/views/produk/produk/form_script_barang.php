<script>
	function set_laba_utama(id) {
		var harga_beli_terakhir = toFloat($('#harga_beli_terakhir').val());
		var harga = toFloat($('#harga_satuan_utama-harga-'+id).val());
		var laba = ((harga - harga_beli_terakhir) / harga_beli_terakhir) * 100;
		$('#harga_satuan_utama-laba_persen-'+id).html(localization.number(laba, 2));
	}

	function set_laba_satuan(id_satuan, id) {
		var harga_beli_terakhir = toFloat($('#harga_beli_terakhir').val());
		var konversi = toFloat($('#harga_satuan-konversi-'+id_satuan+'-'+id).val());
		var harga = toFloat($('#harga_satuan-harga-'+id_satuan+'-'+id).val());
		var laba = (((harga / konversi) - harga_beli_terakhir) / harga_beli_terakhir) * 100;
		$('#harga_satuan-laba_persen-'+id_satuan+'-'+id).html(localization.number(laba, 2));
	}

	function set_harga_utama(id) {
		var margin_persen = toFloat($('#harga_satuan_utama-margin_persen-'+id).val());
		var harga_beli_terakhir = toFloat($('#harga_beli_terakhir').val());
		var harga = harga_beli_terakhir + ((harga_beli_terakhir * margin_persen) / 100);
		$('#harga_satuan_utama-laba_persen-'+id).html(localization.number(margin_persen, 2));
		$('#harga_satuan_utama-harga-'+id).val(harga);
	}

	function set_harga_satuan(id_satuan, id) {
		var konversi = $('#harga_satuan-konversi-'+id_satuan+'-'+id).val();
		var margin_persen = $('#harga_satuan-margin_persen-'+id_satuan+'-'+id).val();
		var harga_beli_terakhir = $('#harga_beli_terakhir').val();
		var harga = toFloat(konversi) * (toFloat(harga_beli_terakhir) + ((toFloat(harga_beli_terakhir) * toFloat(margin_persen)) / 100));
		$('#harga_satuan-laba_persen-'+id_satuan+'-'+id).html(localization.number(margin_persen, 2));
		$('#harga_satuan-harga-'+id_satuan+'-'+id).val(harga);
	}

    function browse_barang() {
        $.ajax({
            url: '<?= $this->route->name('master.barang.browse') ?>',
            success: function(response) {
                bootbox.dialog({
                    title: '{{browse}} {{barang}}',
                    message: response
                });
            }
        });
    }

    function browse_barang_on_dblclick_callback(data) {
        //$('#laba_persen').val('<?= $this->config->item('default_laba') ?>');
        //$('#ppn_persen').val('<?= $this->config->item('default_ppn') ?>');

        $('#id_barang').val(data.id);
        $('#barang').val(data.kode+' - '+data.nama);
        $('#kode').val(data.kode);
        $('#barcode').val(data.barcode);
        $('#produk').val(data.nama);

        $.ajax({
            url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+data.id_satuan,
            success : function(response) {
	            var no_satuan_utama = new Date().getTime();
                var html_harga_satuan = '';
                html_harga_satuan += '<hr>';
	            html_harga_satuan += '<input type="hidden" name="id_satuan" value="'+data.id_satuan+'" id="id_satuan">';
	            html_harga_satuan += '<input type="hidden" name="satuan" value="'+data.satuan+'" id="satuan">';
                html_harga_satuan += '<i class="fa fa-check-square fa-lg"></i> ' + data.satuan + '<br><br>';
                html_harga_satuan += '<table id="table-harga_satuan_utama" class="table table-bordered table-condensed">';
                html_harga_satuan += '<thead><tr>';
                html_harga_satuan += '<th width="150" class="text-center">{{urutan}}</th>';
                html_harga_satuan += '<th width="150" class="text-center">{{jumlah}}</th>';
                html_harga_satuan += '<th width="150" class="text-center">{{margin}}%</th>';
                html_harga_satuan += '<th width="150" class="text-center">{{laba}}%</th>';
                html_harga_satuan += '<th class="text-center">{{harga}}</th>';
                html_harga_satuan += '<th width="1"></th>';
                html_harga_satuan += '</tr></thead>';
                html_harga_satuan += '<tbody>';
                html_harga_satuan += '<tr data-row-id="'+no_satuan_utama+'">';
                html_harga_satuan += '<td>';
                html_harga_satuan += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][utama]" value="1" id="harga_satuan_utama-utama-'+no_satuan_utama+'">';
                html_harga_satuan += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][id_satuan]" value="' + data.id_satuan + '" id="harga_satuan_utama-id_satuan-'+no_satuan_utama+'">';
                html_harga_satuan += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][satuan]" value="' + data.satuan + '" id="harga_satuan_utama-satuan-'+no_satuan_utama+'">';
                html_harga_satuan += '<input type="text" name="harga_satuan_utama['+no_satuan_utama+'][urutan]" value="1" id="harga_satuan_utama-urutan-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly>';
                html_harga_satuan += '</td>';
                html_harga_satuan += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][jumlah]" value="1" id="harga_satuan_utama-jumlah-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly></td>';
                html_harga_satuan += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][margin_persen]" id="harga_satuan_utama-margin_persen-'+no_satuan_utama+'" onkeyup="set_harga_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
                html_harga_satuan += '<td id="harga_satuan_utama-laba_persen-'+no_satuan_utama+'" class="text-center"></td>';
                html_harga_satuan += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][harga]" id="harga_satuan_utama-harga-'+no_satuan_utama+'" onkeyup="set_laba_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
                html_harga_satuan += '<td></td>';
                html_harga_satuan += '</tr>';
                html_harga_satuan += '<tr id="form-add-harga_satuan_utama">';
                html_harga_satuan += '<td>';
                html_harga_satuan += '<input type="hidden" name="form_add_harga_satuan_utama_id_satuan" value="' + data.id_satuan + '" id="harga_satuan_utama-id_satuan-0">';
                html_harga_satuan += '<input type="hidden" name="form_add_harga_satuan_utama_satuan" value="' + data.satuan + '" id="harga_satuan_utama-satuan-0">';
                html_harga_satuan += '<input type="text" name="form_add_harga_satuan_utama_urutan" id="harga_satuan_utama-urutan-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
                html_harga_satuan += '</td>';
                html_harga_satuan += '<td><input type="text" name="form_add_harga_satuan_utama_jumlah" id="harga_satuan_utama-jumlah-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_harga_satuan += '<td><input type="text" name="form_add_harga_satuan_utama_margin_persen" id="harga_satuan_utama-margin_persen-0" onkeyup="set_harga_utama(0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
                html_harga_satuan += '<td id="harga_satuan_utama-laba_persen-0" class="text-center"></td>';
                html_harga_satuan += '<td><input type="text" name="form_add_harga_satuan_utama_harga" id="harga_satuan_utama-harga-0" onkeyup="set_laba_utama(0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
                html_harga_satuan += '<td><button type="button" class="btn btn-primary btn-sm" onclick="harga_satuan_utama_add()"><i class="fa fa-plus"></i></button></td>';
                html_harga_satuan += '</tr>';
                html_harga_satuan += '</tbody>';
                html_harga_satuan += '</table>';

                $.each(response.data, function (key, row) {
                    html_harga_satuan += '<hr>';
                    html_harga_satuan += '<input type="hidden" name="satuan_konversi['+row.id+'][id]" value="' + row.id + '" id="satuan_konversi-id-'+row.id+'">';
                    html_harga_satuan += '<input type="hidden" name="satuan_konversi['+row.id+'][satuan]" value="' + row.satuan + '" id="satuan_konversi-satuan-'+row.id+'">';
                    html_harga_satuan += '<input type="hidden" name="satuan_konversi['+row.id+'][grup]" value="' + row.grup + '" id="satuan_konversi-gruo-'+row.id+'">';
                    html_harga_satuan += '<input type="hidden" name="satuan_konversi['+row.id+'][konversi]" value="' + row.konversi + '" id="satuan_konversi-konversi-'+row.id+'">';
                    html_harga_satuan += '<input type="checkbox" name="satuan_konversi['+row.id+'][satuan_konversi]" id="satuan_konversi-satuan_konversi-'+row.id+'" onclick="harga_add('+row.id+')"> <label>'+ row.satuan +'</label><br><br>';
                    html_harga_satuan += '<table id="table-harga_satuan-'+ row.id +'" class="table table-bordered table-condensed">';
                    html_harga_satuan += '<thead><tr>';
                    html_harga_satuan += '<th width="150" class="text-center">{{urutan}}</th>';
                    html_harga_satuan += '<th width="150" class="text-center">{{jumlah}}</th>';
                    html_harga_satuan += '<th width="150" class="text-center">{{margin}}%</th>';
                    html_harga_satuan += '<th width="150" class="text-center">{{laba}}%</th>';
                    html_harga_satuan += '<th class="text-center">{{harga}}</th>';
                    html_harga_satuan += '<th width="1"></th>';
                    html_harga_satuan += '</tr></thead>';
                    html_harga_satuan += '<tbody>';
                    html_harga_satuan += '</tbody>';
                    html_harga_satuan += '</table>';
                });

                $.ajax({
                    url: '<?= $this->route->name('master.barang.find_hpp_json') ?>/'+data.id,
                    success : function(response) {
                        if (response.data) {
                            var data_hpp = response.data;
                            $('#hpp').val(data_hpp.hpp);
                            $('#harga_min').val(data_hpp.harga_min);
                            $('#harga_max').val(data_hpp.harga_max);
	                        $('#harga_beli_terakhir').val(data.total);
                            set_harga_utama();
                        } else {
                            $('#hpp').val('');
                            $('#harga_min').val('');
                            $('#harga_max').val('');
                            $('#harga_beli_terakhir').val('');
                        }
                    }
                });

                $('#pengaturan_harga_satuan').html(html_harga_satuan);
                $('#pengaturan_harga_satuan').buildForm();
            }
        });
    }

    function harga_satuan_utama_add() {
	    var no_satuan_utama = new Date().getTime();
	    var utama = 0;
	    var id_satuan = $('#harga_satuan_utama-id_satuan-0').val();
	    var satuan = $('#harga_satuan_utama-satuan-0').val();
	    var urutan = $('#harga_satuan_utama-urutan-0').val();
	    var jumlah = $('#harga_satuan_utama-jumlah-0').val();
	    var margin_persen = $('#harga_satuan_utama-margin_persen-0').val();
	    var laba_persen = $('#harga_satuan_utama-laba_persen-0').html();
	    var harga = $('#harga_satuan_utama-harga-0').val();

	    if (urutan == '') {
		    swal('{{urutan_belum_diisi}}');
		    return false;
	    }
	    if (jumlah == '') {
		    swal('{{jumlah_belum_diisi}}');
		    return false;
	    }
	    if (harga == '') {
		    swal('{{harga_belum_diisi}}');
		    return false;
	    }
	    if ($('#table-harga_satuan_utama tbody tr[data-row-id="'+no_satuan_utama+'"]').length == 0) {
		    var html_row = '<tr data-row-id="'+no_satuan_utama+'">';
		    html_row += '<td>';
		    html_row += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][utama]" value="'+utama+'" id="harga_satuan_utama-utama-'+no_satuan_utama+'">';
		    html_row += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][id_satuan]" value="'+id_satuan+'" id="harga_satuan_utama-id_satuan-'+no_satuan_utama+'">';
		    html_row += '<input type="hidden" name="harga_satuan_utama['+no_satuan_utama+'][satuan]" value="'+satuan+'" id="harga_satuan_utama-satuan-'+no_satuan_utama+'">';
		    html_row += '<input type="text" name="harga_satuan_utama['+no_satuan_utama+'][urutan]" value="'+urutan+'" id="harga_satuan_utama-urutan-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
		    html_row += '</td>';
		    html_row += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][jumlah]" value="'+jumlah+'" id="harga_satuan_utama-jumlah-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
		    html_row += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][margin_persen]" value="'+margin_persen+'" id="harga_satuan_utama-margin_persen-'+no_satuan_utama+'" onkeyup="set_harga_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_row += '<td id="harga_satuan_utama-laba_persen-'+no_satuan_utama+'" class="text-center">'+laba_persen+'</td>';
		    html_row += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][harga]" value="'+harga+'" id="harga_satuan_utama-harga-'+no_satuan_utama+'" onkeyup="set_laba_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_utama_remove('+no_satuan_utama+')"><i class="fa fa-trash"></i></button></td>';
		    html_row += '</tr>';
		    $('#form-add-harga_satuan_utama').before(html_row);
		    $('#table-harga_satuan_utama tbody tr[data-row-id]').buildForm();

		    $('#harga_satuan_utama-urutan-0').val('');
		    $('#harga_satuan_utama-jumlah-0').val('');
		    $('#harga_satuan_utama-margin_persen-0').val('');
		    $('#harga_satuan_utama-laba_persen-0').html('');
		    $('#harga_satuan_utama-harga-0').val('');
	    } else {
		    swal('{{harga_satuan_sudah_ada_di_daftar}}');
	    }
    }

    function harga_add(id) {
	    if ($('#satuan_konversi-satuan_konversi-'+id).prop('checked')) {
		    var satuan = $('#satuan_konversi-satuan-'+id).val();
		    var konversi = $('#satuan_konversi-konversi-'+id).val();
		    var html_harga = '<tr id="form-add-harga_satuan-'+ id +'">';
		    html_harga += '<td>';
		    html_harga += '<input type="hidden" name="form_add_harga_satuan_utama_'+id+'" value="0" id="harga_satuan-utama-'+id+'-0">';
		    html_harga += '<input type="hidden" name="form_add_harga_satuan_id_satuan_'+id+'" value="'+id+'" id="harga_satuan-id_satuan-'+id+'-0">';
		    html_harga += '<input type="hidden" name="form_add_harga_satuan_satuan_'+id+'" value="'+satuan+'" id="harga_satuan-satuan-'+id+'-0">';
		    html_harga += '<input type="hidden" name="form_add_harga_satuan_konversi_'+id+'" value="'+konversi+'" id="harga_satuan-konversi-'+id+'-0">';
		    html_harga += '<input type="text" name="form_add_harga_satuan_urutan_'+id+'" value="" id="harga_satuan-urutan-'+id+'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
		    html_harga += '</td>';
		    html_harga += '<td><input type="text" name="form_add_harga_satuan_jumlah_'+id+'" value="" id="harga_satuan-jumlah-'+id+'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
		    html_harga += '<td><input type="text" name="form_add_harga_satuan_margin_persen_'+id+'" value="" id="harga_satuan-margin_persen-'+id+'-0" onkeyup="set_harga_satuan('+id+', 0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_harga += '<td id="harga_satuan-laba_persen-'+id+'-0" class="text-center"></td>';
		    html_harga += '<td><input type="text" name="form_add_harga_satuan_harga_'+id+'" value="" id="harga_satuan-harga-'+id+'-0" onkeyup="set_laba_satuan('+id+', 0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_harga += '<td><button type="button" class="btn btn-primary btn-sm" onclick="harga_satuan_add('+id+')"><i class="fa fa-plus"></i></button></td>';
		    html_harga += '</tr>';

		    $('#table-harga_satuan-'+id+' tbody').html(html_harga).buildForm();
	    } else {
		    $('#table-harga_satuan-'+id+' tbody').html('');
	    }
    }

    function harga_satuan_add(id) {
	    var no_satuan = new Date().getTime();
	    var utama = 0;
	    var id_satuan = $('#harga_satuan-id_satuan-'+id+'-0').val();
	    var satuan = $('#harga_satuan-satuan-'+id+'-0').val();
	    var konversi = $('#harga_satuan-konversi-'+id+'-0').val();
	    var urutan = $('#harga_satuan-urutan-'+id+'-0').val();
	    var jumlah = $('#harga_satuan-jumlah-'+id+'-0').val();
	    var margin_persen = $('#harga_satuan-margin_persen-'+id+'-0').val();
	    var laba_persen = $('#harga_satuan-laba_persen-'+id+'-0').html();
	    var harga = $('#harga_satuan-harga-'+id+'-0').val();

	    if (urutan == '') {
		    swal('{{urutan_belum_diisi}}');
		    return false;
	    }
	    if (jumlah == '') {
		    swal('{{jumlah_belum_diisi}}');
		    return false;
	    }
	    if (harga == '') {
		    swal('{{harga_belum_diisi}}');
		    return false;
	    }
	    if ($('#table-harga_satuan-'+id+' tbody tr[data-row-id="'+no_satuan+'"]').length == 0) {
		    var html_row = '<tr data-row-id="'+no_satuan+'">';
		    html_row += '<td>';
		    html_row += '<input type="hidden" name="harga_satuan['+id+']['+no_satuan+'][utama]" value="'+utama+'" id="harga_satuan-utama-'+id+'-'+no_satuan+'">';
		    html_row += '<input type="hidden" name="harga_satuan['+id+']['+no_satuan+'][id_satuan]" value="'+id_satuan+'" id="harga_satuan-id_satuan-'+id+'-'+no_satuan+'">';
		    html_row += '<input type="hidden" name="harga_satuan['+id+']['+no_satuan+'][satuan]" value="'+satuan+'" id="harga_satuan-satuan-'+id+'-'+no_satuan+'">';
		    html_row += '<input type="hidden" name="harga_satuan['+id+']['+no_satuan+'][konversi]" value="'+konversi+'" id="harga_satuan-konversi-'+id+'-'+no_satuan+'">';
		    html_row += '<input type="text" name="harga_satuan['+id+']['+no_satuan+'][urutan]" value="'+urutan+'" id="harga_satuan-urutan-'+id+'-'+no_satuan+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
		    html_row += '</td>';
		    html_row += '<td><input type="text" name="harga_satuan['+id+']['+no_satuan+'][jumlah]" value="'+jumlah+'" id="harga_satuan-jumlah-'+id+'-'+no_satuan+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
		    html_row += '<td><input type="text" name="harga_satuan['+id+']['+no_satuan+'][margin_persen]" value="'+margin_persen+'" id="harga_satuan-margin_persen-'+id+'-'+no_satuan+'" onkeyup="set_harga_satuan('+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_row += '<td id="harga_satuan-laba_persen-'+id+'-'+no_satuan+'" class="text-center">'+laba_persen+'</td>';
		    html_row += '<td><input type="text" name="harga_satuan['+id+']['+no_satuan+'][harga]" value="'+harga+'" id="harga_satuan-harga-'+id+'-'+no_satuan+'" onkeyup="set_laba_satuan('+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
		    html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_remove('+id+', '+no_satuan+')"><i class="fa fa-trash"></i></button></td>';
		    html_row += '</tr>';
		    $('#form-add-harga_satuan-'+id).before(html_row);
		    $('#table-harga_satuan-'+id+' tbody tr[data-row-id]').buildForm();

		    $('#harga_satuan-urutan-'+id+'-0').val('');
		    $('#harga_satuan-jumlah-'+id+'-0').val('');
		    $('#harga_satuan-margin_persen-'+id+'-0').val('');
		    $('#harga_satuan-laba_persen-'+id+'-0').html('');
		    $('#harga_satuan-harga-'+id+'-0').val('');
	    } else {
		    swal('{{harga_satuan_sudah_ada_di_daftar}}');
	    }
    }

    function harga_satuan_utama_remove(no_satuan_utama) {
        $('#table-harga_satuan_utama tbody tr[data-row-id="'+no_satuan_utama+'"]').remove();
    }

    function harga_satuan_remove(id_satuan, no_satuan) {
        $('#table-harga_satuan-'+id_satuan+' tbody tr[data-row-id="'+no_satuan+'"]').remove();
    }
</script>