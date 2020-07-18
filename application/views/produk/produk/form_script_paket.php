<script>
	$(function () {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});

		$('#form-add-produk-produk').keydown(function(event) {
			if (event.keyCode == 13) {
				$.ajax({
					type: 'post',
					data: 'key='+$('#form-add-produk-produk').val(),
					url: '<?= $this->route->name('produk.produk.find_by_barcode_json') ?>',
					success: function(response) {
						if (response.success) {
							browse_produk_on_dblclick_callback(response.data);
						} else {
							$('#form-add-produk-id_produk_detail').val('');
							$('#form-add-produk-kode_produk').val('');
							$('#form-add-produk-nama_produk').val('');
							$('#form-add-produk-jenis').val('');
							$('#form-add-produk-id_satuan').html('<option value="0">{{pilih}}</option>');
							$('#form-add-produk-jumlah').val(0);
							$('#form-add-produk-nilai').val(0);
						}
					}
				});
			}
		});

		$('#form-add-produk-produk').autocomplete({
			minLength: 3,
			delay: 1000,
			source: function(request, response) {
				$.ajax({
					url: '<?= $this->route->name('produk.produk.get_json') ?>?key='+$('#form-add-produk-produk').val(),
					success: function(data) {
						if (data.success) {
							var produk = [];
							$.each(data.data, function(i, row) {
								produk.push({
									value : row.id,
									label : row.kode+' - '+row.produk,
									data : row
								});
							});
							response(produk);
						}
					}
				});
			},
			focus: function (event, ui) {
				$('#produk-produk-0').val(ui.item.label);
				return false;
			},
			select: function (event, ui) {
				var data = ui.item.data;
				var produk = {
					id_produk : data.id,
					kode_produk : data.kode,
					produk : data.produk,
					jenis_produk : data.jenis
				};
				browse_produk_on_dblclick_callback(produk);
				return false;
			}
		});

	});

    function browse_produk() {
        $.ajax({
            url: '<?= $this->route->name('produk.produk.browse') ?>?jenis=barang|jasa',
            success: function(response) {
                bootbox.dialog({
                    title: '{{browse}} {{produk}}',
                    message: response
                });
            }
        });
    }

    function browse_produk_on_dblclick_callback(data) {
        $('#form-add-produk-id_produk_detail').val(data.id_produk);
        $('#form-add-produk-kode_produk').val(data.kode);
        $('#form-add-produk-nama_produk').val(data.produk);
        $('#form-add-produk-jenis').val(data.jenis_produk);
        $('#form-add-produk-produk').val(data.kode+' - '+data.produk);
        $.ajax({
            url: '<?= $this->route->name('produk.produk.satuan_json') ?>/'+data.id_produk,
            success : function(response) {
                var html_option_id_satuan = '';
                html_option_id_satuan +='<option value="0">{{pilih}}</option>';
                $.each(response.data, function(key, row) {
                    if (row.id) {
                        html_option_id_satuan += '<option value="' + row.id + '">' + row.satuan + '</option>';
                    }
                });
                $('#form-add-produk-id_satuan').html(html_option_id_satuan);
            }
        });
    }

    function harga_add() {
        var no = new Date().getTime();
        var urutan = $('#form-add-harga-urutan').val();
        var jumlah = $('#form-add-harga-jumlah').val();
        var harga = $('#form-add-harga-harga').val();
        var utama = 0;

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
        if ($('#table-harga tbody tr[data-row-id="'+no+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="harga['+no+'][utama]" value="0" id="harga-utama-'+no+'">';
            html_row += '<input type="text" name="harga['+no+'][urutan]" value="'+urutan+'" id="harga-urutan-'+no+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
            html_row += '</td>';
            html_row += '<td><input type="text" name="harga['+no+'][jumlah]" value="'+jumlah+'" id="harga-jumlah-'+no+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="text" name="harga['+no+'][harga]" value="'+harga+'" id="harga-harga-'+no+'" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="harga_remove('+no+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-harga').before(html_row);
            $('#table-harga tbody tr[data-row-id]').buildForm();

            $('#form-add-harga-urutan').val('');
            $('#form-add-harga-jumlah').val('');
            $('#form-add-harga-harga').val('');
        } else {
            swal('{{harga_satuan_sudah_ada_di_daftar}}');
        }
    }

    function produk_add() {
        var id_produk_detail = $('#form-add-produk-id_produk_detail').val();
        var kode_produk = $('#form-add-produk-kode_produk').val();
        var nama_produk = $('#form-add-produk-nama_produk').val();
        var jenis = $('#form-add-produk-jenis').val();
        var produk = $('#form-add-produk-produk').val();
        var id_satuan = $('#form-add-produk-id_satuan').val();
        var jumlah = $('#form-add-produk-jumlah').val();
        var nilai = $('#form-add-produk-nilai').val();

        if (id_produk_detail == '') {
            swal('{{produk_belum_diisi}}');
            return false;
        }
        if (jenis == 'barang' && id_satuan == '') {
            swal('{{satuan_produk_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_produk_harus_diisi}}');
            return false;
        }
        if (nilai == '') {
            swal('{{nilai_produk_harus_diisi}}');
            return false;
        }
        if ($('#table-produk tbody tr[data-row-id="'+id_produk_detail+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+id_produk_detail+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="produk_detail['+id_produk_detail+'][id_produk_detail]" value="'+id_produk_detail+'" id="produk-id_produk-'+id_produk_detail+'">';
            html_row += '<input type="hidden" name="produk_detail['+id_produk_detail+'][kode_produk]" value="'+kode_produk+'" id="produk-kode_produk-'+id_produk_detail+'">';
            html_row += '<input type="hidden" name="produk_detail['+id_produk_detail+'][nama_produk]" value="'+nama_produk+'" id="produk-nama_produk-'+id_produk_detail+'">';
            html_row += '<input type="hidden" name="produk_detail['+id_produk_detail+'][jenis]" value="'+jenis+'" id="produk-jenis-'+id_produk_detail+'">';
            html_row += produk;
            html_row += '</td>';
            html_row += '<td><select name="produk_detail['+id_produk_detail+'][id_satuan]" id="produk-id_satuan-'+id_produk_detail+'" class="form-control input-sm"></select></td>';
            html_row += '<td><input type="text" name="produk_detail['+id_produk_detail+'][jumlah]" value="'+localization.number(jumlah, "")+'"  id="produk-jumlah-'+id_produk_detail+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="text" name="produk_detail['+id_produk_detail+'][nilai]" value="'+localization.number(nilai, "")+'"  id="produk-nilai-'+id_produk_detail+'" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="produk_remove('+id_produk_detail+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-produk').before(html_row);
            $('#produk-id_satuan-'+id_produk_detail).html($('#form-add-produk-id_satuan').html()).val(id_satuan);
            $('#table-produk tbody tr[data-row-id]').buildForm();
            $('#form-add-produk-id_produk_detail').val('');
            $('#form-add-produk-kode_produk').val('');
            $('#form-add-produk-nama_produk').val('');
            $('#form-add-produk-jenis').val('');
            $('#form-add-produk-produk').val('');
            $('#form-add-produk-id_satuan').html('<option value="0">{{pilih}}</option>');
            $('#form-add-produk-jumlah').val('');
            $('#form-add-produk-nilai').val('');
        } else {
            swal('{{produk_sudah_ada_di_daftar}}');
        }
    }

    function harga_remove(no) {
        $('#table-harga tbody tr[data-row-id="'+no+'"]').remove();
    }

    function produk_remove(id_produk_detail) {
        $('#table-produk tbody tr[data-row-id="'+id_produk_detail+'"]').remove();
    }

</script>