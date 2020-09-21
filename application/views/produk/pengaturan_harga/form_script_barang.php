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

	function set_cabang_laba_utama(id_cabang, id) {
		var harga_beli_terakhir = toFloat($('#cabang-harga_beli_terakhir-'+id_cabang).val());
		var harga = toFloat($('#cabang-harga_satuan_utama-harga-'+id_cabang+'-'+id).val());
		var laba = ((harga - harga_beli_terakhir) / harga_beli_terakhir) * 100;
		$('#cabang-harga_satuan_utama-laba_persen-'+id_cabang+'-'+id).html(localization.number(laba, 2));
	}

	function set_cabang_laba_satuan(id_cabang, id_satuan, id) {
		var harga_beli_terakhir = toFloat($('#cabang-harga_beli_terakhir-'+id_cabang).val());
		var konversi = toFloat($('#cabang-harga_satuan-konversi-'+id_cabang+'-'+id_satuan+'-'+id).val());
		var harga = toFloat($('#cabang-harga_satuan-harga-'+id_cabang+'-'+id_satuan+'-'+id).val());
		var laba = (((harga / konversi) - harga_beli_terakhir) / harga_beli_terakhir) * 100;
		$('#cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id_satuan+'-'+id).html(localization.number(laba, 2));
	}

	function set_cabang_harga_utama(id_cabang, id) {
		var harga_beli_terakhir = toFloat($('#cabang-harga_beli_terakhir-'+id_cabang).val());
		var margin_persen = toFloat($('#cabang-harga_satuan_utama-margin_persen-'+id_cabang+'-'+id).val());
		var harga = harga_beli_terakhir + ((harga_beli_terakhir * margin_persen) / 100);
		$('#cabang-harga_satuan_utama-laba_persen-'+id_cabang+'-'+id).html(localization.number(margin_persen, 2));
		$('#cabang-harga_satuan_utama-harga-'+id_cabang+'-'+id).val(harga);
	}

	function set_cabang_harga_satuan(id_cabang, id_satuan, id) {
		var harga_beli_terakhir = $('#cabang-harga_beli_terakhir-'+id_cabang).val();
		var konversi = $('#cabang-harga_satuan-konversi-'+id_cabang+'-'+id_satuan+'-'+id).val();
		var margin_persen = $('#cabang-harga_satuan-margin_persen-'+id_cabang+'-'+id_satuan+'-'+id).val();
		var harga = toFloat(konversi) * (toFloat(harga_beli_terakhir) + ((toFloat(harga_beli_terakhir) * toFloat(margin_persen)) / 100));
		$('#cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id_satuan+'-'+id).html(localization.number(margin_persen, 2));
		$('#cabang-harga_satuan-harga-'+id_cabang+'-'+id_satuan+'-'+id).val(harga);
	}

    function harga_cabang_add(id_cabang) {
        if ($('#cabang_harga-id_cabang-'+id_cabang).prop('checked')) {
            $('#harga_cabang-'+id_cabang).show();
            set_cabang_harga_utama(id_cabang);
        } else {
            $('#harga_cabang-'+id_cabang).hide();
        }
    }

    function harga_satuan_utama_add() {
        var no_satuan_utama = new Date().getTime();
        var utama = 0;
        var id_satuan = $('#harga_satuan_utama-id_satuan-0').val();
        var satuan = $('#harga_satuan_utama-satuan-0').val();
        var urutan = $('#harga_satuan_utama-urutan-0').val();
        var jumlah = $('#harga_satuan_utama-jumlah-0').val();
        var margin_persen = $('#harga_satuan_utama-margin_persen-0').val();
        var margin_persen_atas = $('#harga_satuan_utama-margin_persen_atas-0').val();
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
            html_row += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][margin_persen_atas]" value="'+margin_persen_atas+'" id="harga_satuan_utama-margin_persen_atas-'+no_satuan_utama+'" onkeyup="set_harga_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
	        html_row += '<td id="harga_satuan_utama-laba_persen-'+no_satuan_utama+'" class="text-center">'+laba_persen+'</td>';
            html_row += '<td><input type="text" name="harga_satuan_utama['+no_satuan_utama+'][harga]" value="'+harga+'" id="harga_satuan_utama-harga-'+no_satuan_utama+'" onkeyup="set_laba_utama('+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_utama_remove('+no_satuan_utama+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-harga_satuan_utama').before(html_row);
            $('#table-harga_satuan_utama tbody tr[data-row-id]').buildForm();

            $('#harga_satuan_utama-urutan-0').val('');
            $('#harga_satuan_utama-jumlah-0').val('');
            $('#harga_satuan_utama-margin_persen-0').val('');
            $('#harga_satuan_utama-margin_persen_atas-0').val('');
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
            html_harga += '<td><input type="text" name="form_add_harga_satuan_margin_persen_atas_'+id+'" value="" id="harga_satuan-margin_persen_atas-'+id+'-0" onkeyup="set_harga_satuan('+id+', 0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
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
        var margin_persen_atas = $('#harga_satuan-margin_persen_atas-'+id+'-0').val();
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
            html_row += '<td><input type="text" name="harga_satuan['+id+']['+no_satuan+'][margin_persen_atas]" value="'+margin_persen_atas+'" id="harga_satuan-margin_persen_atas-'+id+'-'+no_satuan+'" onkeyup="set_harga_satuan('+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
	        html_row += '<td id="harga_satuan-laba_persen-'+id+'-'+no_satuan+'" class="text-center">'+laba_persen+'</td>';
	        html_row += '<td><input type="text" name="harga_satuan['+id+']['+no_satuan+'][harga]" value="'+harga+'" id="harga_satuan-harga-'+id+'-'+no_satuan+'" onkeyup="set_laba_satuan('+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_remove('+id+', '+no_satuan+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-harga_satuan-'+id).before(html_row);
            $('#table-harga_satuan-'+id+' tbody tr[data-row-id]').buildForm();

            $('#harga_satuan-urutan-'+id+'-0').val('');
            $('#harga_satuan-jumlah-'+id+'-0').val('');
            $('#harga_satuan-margin_persen-'+id+'-0').val('');
            $('#harga_satuan-margin_persen_atas-'+id+'-0').val('');
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

    function cabang_harga_satuan_utama_add(id_cabang) {
        var no_satuan_utama = new Date().getTime();
        var utama = 0;
        var id_satuan = $('#cabang-harga_satuan_utama-id_satuan-'+id_cabang+'-0').val();
        var satuan = $('#cabang-harga_satuan_utama-satuan-'+id_cabang+'-0').val();
        var urutan = $('#cabang-harga_satuan_utama-urutan-'+id_cabang+'-0').val();
        var jumlah = $('#cabang-harga_satuan_utama-jumlah-'+id_cabang+'-0').val();
        var margin_persen = $('#cabang-harga_satuan_utama-margin_persen-'+id_cabang+'-0').val();
        var margin_persen_atas = $('#cabang-harga_satuan_utama-margin_persen_atas-'+id_cabang+'-0').val();
        var laba_persen = $('#cabang-harga_satuan_utama-laba_persen-'+id_cabang+'-0').html();
        var harga = $('#cabang-harga_satuan_utama-harga-'+id_cabang+'-0').val();
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
        if ($('#table-cabang_harga_satuan_utama-'+id_cabang+' tbody tr[data-row-id="'+no_satuan_utama+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no_satuan_utama+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][utama]" value="'+utama+'" id="cabang-harga_satuan_utama-utama-'+id_cabang+'-'+no_satuan_utama+'">';
            html_row += '<input type="hidden" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][id_satuan]" value="'+id_satuan+'" id="cabang-harga_satuan_utama-id_satuan-'+id_cabang+'-'+no_satuan_utama+'">';
            html_row += '<input type="hidden" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][satuan]" value="'+satuan+'" id="cabang-harga_satuan_utama-satuan-'+id_cabang+'-'+no_satuan_utama+'">';
            html_row += '<input type="text" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][urutan]" value="'+urutan+'" id="cabang-harga_satuan_utama-urutan-'+id_cabang+'-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
            html_row += '</td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][jumlah]" value="'+jumlah+'" id="cabang-harga_satuan_utama-jumlah-'+id_cabang+'-'+no_satuan_utama+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][margin_persen]" value="'+margin_persen+'" id="cabang-harga_satuan_utama-margin_persen-'+id_cabang+'-'+no_satuan_utama+'" onkeyup="set_cabang_harga_utama('+id_cabang+', '+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][margin_persen_atas]" value="'+margin_persen_atas+'" id="cabang-harga_satuan_utama-margin_persen_atas-'+id_cabang+'-'+no_satuan_utama+'" onkeyup="set_cabang_harga_utama('+id_cabang+', '+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td id="cabang-harga_satuan_utama-laba_persen-'+id_cabang+'-'+no_satuan_utama+'" class="text-center">'+laba_persen+'</td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan_utama['+id_cabang+']['+no_satuan_utama+'][harga]" value="'+harga+'" id="cabang-harga_satuan_utama-harga-'+id_cabang+'-'+no_satuan_utama+'" onkeyup="set_cabang_laba_utama('+id_cabang+', '+no_satuan_utama+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_satuan_utama_remove('+id_cabang+','+no_satuan_utama+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-cabang_harga_satuan_utama-'+id_cabang).before(html_row);
            $('#table-cabang-harga_satuan_utama-'+id_cabang+' tbody tr[data-row-id]').buildForm();
            $('#cabang-harga_satuan_utama-urutan-'+id_cabang+'-0').val('');
            $('#cabang-harga_satuan_utama-jumlah-'+id_cabang+'-0').val('');
            $('#cabang-harga_satuan_utama-margin_persen-'+id_cabang+'-0').val('');
            $('#cabang-harga_satuan_utama-margin_persen_atas-'+id_cabang+'-0').val('');
            $('#cabang-harga_satuan_utama-laba_persen-'+id_cabang+'-0').html('');
            $('#cabang-harga_satuan_utama-harga-'+id_cabang+'-0').val('');
        } else {
            swal('{{harga_satuan_sudah_ada_di_daftar}}');
        }
    }

    function cabang_harga_add(id_cabang, id_satuan) {
        if ($('#satuan_konversi-satuan_konversi-'+id_cabang+'-'+id_satuan).prop('checked')) {
            var konversi = $('#satuan_konversi-konversi-'+id_cabang+'-'+id_satuan).val();
            var satuan = $('#satuan_konversi-satuan-'+id_cabang+'-'+id_satuan).val();
            var html_harga = '<tr id="form-add-cabang_harga_satuan-'+id_cabang+'-'+ id_satuan +'">';
            html_harga += '<td>';
            html_harga += '<input type="hidden" name="form_add_cabang_harga_satuan_utama_'+id_cabang+'_'+id_satuan+'" value="0" id="cabang-harga_satuan-utama-'+id_cabang+'-'+id_satuan+'-0">';
            html_harga += '<input type="hidden" name="form_add_cabang_harga_satuan_id_satuan_'+id_cabang+'_'+id_satuan+'" value="'+id_satuan+'" id="cabang-harga_satuan-id_satuan-'+id_cabang+'-'+id_satuan+'-0">';
            html_harga += '<input type="hidden" name="form_add_cabang_harga_satuan_satuan_'+id_cabang+'_'+id_satuan+'" value="'+satuan+'" id="cabang-harga_satuan-satuan-'+id_cabang+'-'+id_satuan+'-0">';
            html_harga += '<input type="hidden" name="form_add_cabang_harga_satuan_konversi_'+id_cabang+'_'+id_satuan+'" value="'+konversi+'" id="cabang-harga_satuan-konversi-'+id_cabang+'-'+id_satuan+'-0">';
            html_harga += '<input type="text" name="form_add_cabang_harga_satuan_urutan_'+id_cabang+'_'+id_satuan+'" value="" id="cabang-harga_satuan-urutan-'+id_cabang+'-'+id_satuan+'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
            html_harga += '</td>';
            html_harga += '<td><input type="text" name="form_add_cabang_harga_satuan_jumlah_'+id_cabang+'_'+id_satuan+'" value="" id="cabang-harga_satuan-jumlah-'+id_cabang+'-'+id_satuan+'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_harga += '<td><input type="text" name="form_add_cabang_harga_satuan_margin_persen_'+id_cabang+'_'+id_satuan+'" value="" id="cabang-harga_satuan-margin_persen-'+id_cabang+'-'+id_satuan+'-0" onkeyup="set_cabang_harga_satuan('+id_cabang+', '+id_satuan+', 0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
	        html_harga += '<td id="cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id_satuan+'-0" class="text-center"></td>';
            html_harga += '<td><input type="text" name="form_add_cabang_harga_satuan_harga_'+id_cabang+'_'+id_satuan+'" value="" id="cabang-harga_satuan-harga-'+id_cabang+'-'+id_satuan+'-0" onkeyup="set_cabang_laba_satuan('+id_cabang+', '+id_satuan+', 0)" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_harga += '<td><button type="button" class="btn btn-primary btn-sm" onclick="cabang_harga_satuan_add('+id_cabang+','+id_satuan+')"><i class="fa fa-plus"></i></button></td>';
            html_harga += '</tr>';
            $('#table-cabang-harga_satuan-'+id_cabang+'-'+id_satuan+' tbody').html(html_harga).buildForm();
        } else {
            $('#table-cabang-harga_satuan-'+id_cabang+'-'+id_satuan+' tbody').html('');
        }
    }

    function cabang_harga_satuan_add(id_cabang, id) {
        var no_satuan = new Date().getTime();
        var utama = $('#cabang-harga_satuan-utama-'+id_cabang+'-'+id+'-0').val();
        var id_satuan = $('#cabang-harga_satuan-id_satuan-'+id_cabang+'-'+id+'-0').val();
        var satuan = $('#cabang-harga_satuan-satuan-'+id_cabang+'-'+id+'-0').val();
        var konversi = $('#cabang-harga_satuan-konversi-'+id_cabang+'-'+id+'-0').val();
        var urutan = $('#cabang-harga_satuan-urutan-'+id_cabang+'-'+id+'-0').val();
        var jumlah = $('#cabang-harga_satuan-jumlah-'+id_cabang+'-'+id+'-0').val();
        var margin_persen = $('#cabang-harga_satuan-margin_persen-'+id_cabang+'-'+id+'-0').val();
        var margin_persen_atas = $('#cabang-harga_satuan-margin_persen_atas-'+id_cabang+'-'+id+'-0').val();
        var laba_persen = $('#cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id+'-0').html();
        var harga = $('#cabang-harga_satuan-harga-'+id_cabang+'-'+id+'-0').val();
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
        if ($('#table-cabang-harga_satuan-'+id_cabang+'-'+id+' tbody tr[data-row-id="'+no_satuan+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no_satuan+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][utama]" value="'+utama+'" id="cabang-harga_satuan-utama-'+id_cabang+'-'+id+'-'+no_satuan+'">';
            html_row += '<input type="hidden" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][id_satuan]" value="'+id_satuan+'" id="cabang-harga_satuan-id_satuan-'+id_cabang+'-'+id+'-'+no_satuan+'">';
            html_row += '<input type="hidden" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][satuan]" value="'+satuan+'" id="cabang-harga_satuan-satuan-'+id_cabang+'-'+id+'-'+no_satuan+'">';
            html_row += '<input type="hidden" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][konversi]" value="'+konversi+'" id="cabang-harga_satuan-konversi-'+id_cabang+'-'+id+'-'+no_satuan+'">';
            html_row += '<input type="text" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][urutan]" value="'+urutan+'" id="cabang-harga_satuan-urutan-'+id_cabang+'-'+id+'-'+no_satuan+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
            html_row += '</td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][jumlah]" value="'+jumlah+'" id="cabang-harga_satuan-jumlah-'+id_cabang+'-'+id+'-'+no_satuan+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][margin_persen]" value="'+margin_persen+'" id="cabang-harga_satuan-margin_persen-'+id_cabang+'-'+id+'-'+no_satuan+'" onkeyup="set_cabang_harga_satuan('+id_cabang+', '+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][margin_persen_atas]" value="'+margin_persen_atas+'" id="cabang-harga_satuan-margin_persen_atas-'+id_cabang+'-'+id+'-'+no_satuan+'" onkeyup="set_cabang_harga_satuan('+id_cabang+', '+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
	        html_row += '<td id="cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id+'-'+no_satuan+'" class="text-center">'+laba_persen+'</td>';
            html_row += '<td><input type="text" name="cabang_harga_satuan['+id_cabang+']['+id+']['+no_satuan+'][harga]" value="'+harga+'" id="cabang-harga_satuan-harga-'+id_cabang+'-'+id+'-'+no_satuan+'" onkeyup="set_cabang_laba_satuan('+id_cabang+', '+id+', '+no_satuan+')" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_satuan_remove('+id_cabang+','+id+','+no_satuan+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-cabang_harga_satuan-'+id_cabang+'-'+id).before(html_row);
            $('#table-cabang-harga_satuan-'+id_cabang+'-'+id+' tbody tr[data-row-id]').buildForm();
            $('#cabang-harga_satuan-urutan-'+id_cabang+'-'+id+'-0').val('');
            $('#cabang-harga_satuan-jumlah-'+id_cabang+'-'+id+'-0').val('');
            $('#cabang-harga_satuan-margin_persen-'+id_cabang+'-'+id+'-0').val('');
            $('#cabang-harga_satuan-margin_persen_atas-'+id_cabang+'-'+id+'-0').val('');
            $('#cabang-harga_satuan-laba_persen-'+id_cabang+'-'+id+'-0').html('');
            $('#cabang-harga_satuan-harga-'+id_cabang+'-'+id+'-0').val('');
        } else {
            swal('{{harga_satuan_sudah_ada_di_daftar}}');
        }
    }

    function cabang_harga_satuan_utama_remove(id_cabang, no_satuan_utama) {
        $('#table-cabang-harga_satuan_utama-'+id_cabang+' tbody tr[data-row-id="'+no_satuan_utama+'"]').remove();
    }

    function cabang_harga_satuan_remove(id_cabang, id_satuan, no_satuan) {
        $('#table-cabang-harga_satuan-'+id_cabang+'-'+id_satuan+' tbody tr[data-row-id="'+no_satuan+'"]').remove();
    }

</script>