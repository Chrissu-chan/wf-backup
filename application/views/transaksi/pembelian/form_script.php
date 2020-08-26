<script>
    $(function () {
        $('#tanggal').inputmask('99-99-9999');
	    document.title = 'Pembelian Total : 0';
        
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

	    $('#barang-id_satuan-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-jumlah-0').select();
			    return false;
		    }
	    });

	    $('#barang-jumlah-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-harga-0').select();
			    return false;
		    }
	    });

	    $('#barang-harga-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-subtotal-0').select();
			    return false;
		    }
	    });

	    $('#barang-diskon_persen-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-potongan-0').select();
			    return false;
		    }
	    });

	    $('#barang-potongan-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-total-0').select();
			    return false;
		    }
	    });

	    $('#barang-subtotal-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-diskon_persen-0').select();
			    return false;
		    }
	    });

	    $('#barang-total-0').keydown(function (event) {
		    if (event.keyCode == 13) {
                $('#barang-expired-0').select();
                return false;
		    }
	    });

	    $('#barang-expired-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-batch_number-0').select();
			    return false;
		    }
	    });

	    $('#barang-batch_number-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    barang_add();
		    }
	    });

        $('#btn-add_barang').keydown(function (event) {
            if (event.keyCode == 13) {
                barang_add();
            }
        });

        $('#barang-barang-0').keydown(function(event) {
            if (event.keyCode == 13) {
                $.ajax({
                    type: 'post',
                    data: 'key='+$('#barang-barang-0').val(),
                    url: '<?= $this->route->name('master.barang.find_by_barcode_json') ?>',
                    success: function(response) {
                        if (response.success) {
                            browse_barang_on_dblclick_callback(response.data);
                        } else {
                            $('#barang-id_barang-0').val('');
                            $('#barang-kode_barang-0').val('');
                            $('#barang-nama_barang-0').val('');
                            $('#barang-jenis_barang-0').val('');
                            $('#barang-jumlah-0').val(0);
                            $('#barang-id_satuan-0').html('<option value="0">{{pilih}}</option>');
                        }
                    }
                });
            }
        });

        $('#barang-barang-0').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '<?= $this->route->name('master.barang.get_json') ?>?key='+$('#barang-barang-0').val(),
                    success: function(data) {
                        if (data.success) {
                            var barang = [];
                            $.each(data.data, function(i, row) {
                                barang.push({
                                    value : row.id,
                                    label : row.kode+' - '+row.nama,
                                    data : row
                                });
                            });
                            response(barang);
                        }
                    }
                });
            },
            focus: function (event, ui) {
                $('#barang-barang-0').val(ui.item.data.nama);
                return false;
            },
            select: function (event, ui) {
                browse_barang_on_dblclick_callback(ui.item.data);
                return false;
            }
        });

	    $('#id_supplier').select2({
		    ajax: {
			    url: '<?= $this->route->name('supplier.supplier.get_json') ?>',
			    type: 'get',
			    dataType: 'json',
			    delay: 50,
			    processResults: function (response) {
				    var supplier = [];
				    $.each(response.data, function(i, row) {
					    supplier.push({
						    id : row.id,
						    text : row.supplier
					    });
				    });
				    return {
					    results: supplier
				    };
			    },
			    cache: true
		    }
	    });
    });

    function store() {
	    swalConfirm('{{confirm_pembelian_store}}', function() {
		    $('#frm-pembelian').submit();
	    });
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
        $('#barang-id_barang-0').val(data.id);
        $('#barang-kode_barang-0').val(data.kode);
        $('#barang-nama_barang-0').val(data.nama);
        $('#barang-barang-0').val(data.nama);
        $('#barang-barang_id_satuan-0').val(data.id_satuan);
        $('#barang-barang_satuan-0').val(data.satuan);
        $('#barang-jumlah-0').val(1);
        $.ajax({
            url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+data.id_satuan,
            success : function(response) {
                var html_option_id_satuan = '';
                html_option_id_satuan +='<option value="0">{{pilih}}</option>';
	            if (data.id_satuan == data.id_satuan_beli) {
		            html_option_id_satuan+='<option value="'+data.id_satuan+'" selected>'+data.satuan+'</option>';
	            } else {
		            html_option_id_satuan+='<option value="'+data.id_satuan+'">'+data.satuan+'</option>';
	            }
                $.each(response.data, function(key, row) {
	                if (row.id == data.id_satuan_beli) {
		                html_option_id_satuan+='<option value="'+row.id+'" selected>'+row.satuan+'</option>';
	                } else {
		                html_option_id_satuan+='<option value="'+row.id+'">'+row.satuan+'</option>';
	                }
                });
                $('#barang-id_satuan-0').html(html_option_id_satuan).focus();
	            barang_get_harga(0);
            }
        });
    }

    function barang_get_harga(key) {
	    var id_barang = $('#barang-id_barang-'+key).val();
	    var id_satuan = $('#barang-id_satuan-'+key).val();
	    $.ajax({
		    url: '<?= $this->route->name('master.obat.find_json') ?>?id_barang='+id_barang+'&id_satuan='+id_satuan,
		    success : function(response) {
			    if (response.success) {
				    var data = response.data;
				    $('#barang-jumlah-'+key).val(1);
				    $('#barang-harga-'+key).val(data.hpp);
				    $('#barang-diskon_persen-'+key).val(data.diskon_persen);
				    $('#barang-ppn_persen-'+key).val(data.ppn_persen);
				    barang_set_subtotal(key);
			    }
		    }
	    });
    }

    function barang_add() {
	    $('#barang-barang-0').select();

        var no = new Date().getTime();
        var id_barang = $('#barang-id_barang-0').val();
        var kode_barang = $('#barang-kode_barang-0').val();
        var nama_barang = $('#barang-nama_barang-0').val();
        var barang = $('#barang-barang-0').val();
        var id_satuan = $('#barang-id_satuan-0').val();
        var barang_id_satuan = $('#barang-barang_id_satuan-0').val();
        var barang_satuan = $('#barang-barang_satuan-0').val();
        var expired = $('#barang-expired-0').val();
        var batch_number = $('#barang-batch_number-0').val();
        var jumlah = $('#barang-jumlah-0').val();
        var harga = localization.number($('#barang-harga-0').val());
        var diskon = $('#barang-diskon_rp-0').val();
        var diskon_persen = $('#barang-diskon_persen-0').val();
        var potongan = $('#barang-potongan-0').val();
        var subtotal = localization.number($('#barang-subtotal-0').val());
        var ppn = $('#barang-ppn_rp-0').val();
        var ppn_persen = $('#barang-ppn_persen-0').val();
        var total = localization.number($('#barang-total-0').val());

        if (id_barang == '') {
            swal('{{barang_belum_diisi}}');
            return false;
        }
        if (id_satuan == '') {
            swal('{{satuan_barang_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_barang_harus_diisi}}');
            return false;
        }
        if ($('tr[data-row-id="'+no+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no+'">';
                html_row += '<td>';
                html_row += '<input type="hidden" name="pembelian_barang['+no+'][id_barang]" value="'+id_barang+'" id="barang-id_barang-'+no+'">';
                html_row += '<input type="hidden" name="pembelian_barang['+no+'][kode_barang]" value="'+kode_barang+'" id="barang-kode_barang-'+no+'">';
                html_row += '<input type="hidden" name="pembelian_barang['+no+'][nama_barang]" value="'+nama_barang+'" id="barang-nama_barang-'+no+'">';
                html_row += barang;
                html_row += '</td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="pembelian_barang['+no+'][barang_id_satuan]" value="'+barang_id_satuan+'" id="barang-barang_id_satuan-'+no+'">';
		        html_row += '<input type="hidden" name="pembelian_barang['+no+'][barang_satuan]" value="'+barang_satuan+'" id="barang-barang_satuan-'+no+'">';
		        html_row += '<select name="pembelian_barang['+no+'][id_satuan]" id="barang-id_satuan-'+no+'" onchange="barang_get_harga('+no+')" class="form-control input-sm"></select>';
		        html_row += '</td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][jumlah]" value="'+jumlah+'" id="barang-jumlah-'+no+'" class="form-control input-sm text-center" onkeyup="barang_set_subtotal('+no+')" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][harga]" value="'+harga+'" id="barang-harga-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_subtotal('+no+')" data-input-type="number-format"></td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="pembelian_barang['+no+'][ppn]" value="'+ppn+'" id="barang-ppn_rp-'+no+'">';
		        html_row += '<input type="hidden" name="pembelian_barang['+no+'][ppn_persen]" value="'+ppn_persen+'" id="barang-ppn_persen-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_total('+no+')" data-input-type="number-format">';
		        html_row += '<input type="text" name="pembelian_barang['+no+'][subtotal]" value="'+subtotal+'" id="barang-subtotal-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_harga_total('+no+')" data-input-type="number-format">';
		        html_row += '</td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="pembelian_barang['+no+'][diskon]" value="'+diskon+'" id="barang-diskon_rp-'+no+'">';
		        html_row += '<input type="text" name="pembelian_barang['+no+'][diskon_persen]" value="'+diskon_persen+'" id="barang-diskon_persen-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_total('+no+')" data-input-type="number-format">';
		        html_row += '</td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][potongan]" value="'+potongan+'" id="barang-potongan-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_total('+no+')" data-input-type="number-format"></td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][total]" value="'+total+'" id="barang-total-'+no+'" class="form-control input-sm text-right" onkeyup="barang_set_harga('+no+')" data-input-type="number-format"></td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][expired]" value="'+expired+'" id="barang-expired-'+no+'" class="form-control input-sm" data-input-type="datepicker"></td>';
                html_row += '<td><input type="text" name="pembelian_barang['+no+'][batch_number]" value="'+batch_number+'" id="barang-batch_number-'+no+'" class="form-control input-sm"></td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove('+no+')"><i class="fa fa-trash"></i></button></td>';

            $('#table-barang tbody').append(html_row);
            $('#barang-id_satuan-'+no).html($('#barang-id_satuan-0').html()).val(id_satuan);
            $('#table-barang tbody tr[data-row-id]').buildForm();
            $('#barang-id_barang-0').val('');
            $('#barang-kode_barang-0').val('');
            $('#barang-nama_barang-0').val('');
            $('#barang-barang-0').val('');
            $('#barang-barang_id_satuan-0').val('');
            $('#barang-barang_satuan-0').val('');
            $('#barang-id_satuan-0').html('<option value="0">{{pilih}}</option>');
            $('#barang-jumlah-0').val('');
            $('#barang-harga-0').val('');
            $('#barang-diskon_rp-0').val('');
            $('#barang-diskon_persen-0').val($('#diskon_persen').val());
            $('#barang-potongan-0').val('');
            $('#barang-subtotal-0').val('');
            $('#barang-ppn_rp-0').val('');
            $('#barang-ppn_persen-0').val($('#ppn_persen').val());
            $('#barang-total-0').val('');
            $('#barang-expired-0').val('<?= date('d-m-Y', strtotime(date('Y-m-d') . ' + 1 year')) ?>');
            $('#barang-batch_number-0').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function barang_set_harga(id) {
	    var jumlah = toFloat($('#barang-jumlah-'+id).val());
	    var total = toFloat($('#barang-total-'+id).val());
        var diskon_persen = toFloat($('#barang-diskon_persen-'+id).val());
        var potongan = toFloat($('#barang-potongan-'+id).val());
	    var ppn_persen = toFloat($('#barang-ppn_persen-'+id).val());

	    var subtotal = total / jumlah;
	    subtotal -= potongan;
	    var diskon = subtotal - ((100 / (100 - diskon_persen)) * subtotal);
	    subtotal -= diskon;
	    var ppn = subtotal - ((100 / (100 + ppn_persen)) * subtotal);
	    var harga = subtotal - ppn;
        $('#barang-diskon_rp-'+id).val(diskon);
        $('#barang-ppn_rp-'+id).val(ppn);
        $('#barang-subtotal-'+id).val(subtotal);
	    $('#barang-harga-'+id).val(harga);
    }

    function barang_set_harga_total(id) {
	    var subtotal = toFloat($('#barang-subtotal-'+id).val());
	    var ppn_persen = toFloat($('#barang-ppn_persen-'+id).val());
	    var ppn = subtotal - ((100 / (100 + ppn_persen)) * subtotal);
	    var harga = subtotal - ppn;
        $('#barang-harga-'+id).val(harga);
        barang_set_total(id);
    }

    function barang_set_subtotal(id) {
	    var harga = toFloat($('#barang-harga-'+id).val());
	    var ppn_persen = toFloat($('#barang-ppn_persen-'+id).val());
	    var ppn = (harga * ppn_persen) / 100;
	    $('#barang-ppn_rp-'+id).val(ppn);
	    $('#barang-subtotal-'+id).val(harga + ppn);
        barang_set_total(id);
    }

    function barang_set_total(id) {
	    var jumlah = toFloat($('#barang-jumlah-'+id).val());
	    var subtotal = toFloat($('#barang-subtotal-'+id).val());
	    var diskon_persen = toFloat($('#barang-diskon_persen-'+id).val());
	    var diskon = (diskon_persen / 100) * subtotal;
	    var potongan = toFloat($('#barang-potongan-'+id).val());
	    $('#barang-diskon_rp-'+id).val(diskon);
	    $('#barang-total-'+id).val(((subtotal - diskon) - potongan) * jumlah);
	    set_subtotal();
    }

    function set_subtotal() {
        var subtotal = 0;
        $.each($("input[id*='barang-subtotal']"), function() {
            subtotal += toFloat($(this).val());
        });
        $('#subtotal').val(subtotal);
	    //$('#label-subtotal').html(localization.number($('#subtotal').val()));
        set_total();
    }

    function set_ppn() {
        var ppn = 0;
        $.each($("input[id*='barang-ppn_rp']"), function() {
            ppn += toFloat($(this).val());
        });
        $('#ppn').val(ppn);
	    $('#label-ppn').html(localization.number($('#ppn').val()));
        set_total();
    }

    function set_diskon_persen() {
        var diskon_persen = toFloat($('#diskon_persen').val());
        $.each($('[data-row-id]'), function(i, elem) {
            var row_id = $(elem).data('row-id');
            $('#barang-diskon_persen-'+row_id, elem).val(diskon_persen);
            barang_set_subtotal(row_id);
        });
    }

    function set_ppn_persen() {
        var ppn_persen = toFloat($('#ppn_persen').val());
        $.each($('[data-row-id]'), function(i, elem) {
            var row_id = $(elem).data('row-id');
            $('#barang-ppn_persen-'+row_id, elem).val(ppn_persen);
            barang_set_subtotal(row_id);
        });
    }

    function set_total() {
	    var total = 0;
	    $.each($("input[id*='barang-total']"), function() {
		    total += toFloat($(this).val());
	    });
        $('#total').val(total);
	    $('#label-total').html(localization.number($('#total').val()));
	    document.title = 'Pembelian Total : '+localization.number($('#total').val());
    }

    function set_pembayaran() {
        var total = toFloat($('#total').val());
        var uang_muka = toFloat($('#uang_muka').val());
        if (uang_muka > total) {
            $('#uang_muka').val(total);
        }
    }

    function set_metode_pembayaran() {
        if ($('#metode_pembayaran').val() == 'utang') {
            $('#utang').show();
            $('#jatuh_tempo').val('<?= date('d-m-Y', strtotime('+1 month')) ?>');
        } else {
            $('#utang').hide();
            $('#jatuh_tempo').val('');
            $('#uang_muka').val('');
        }
    }

    function barang_remove(id) {
        swalConfirm('{{confirm_pembelian_barang_delete}}', function() {
            $('#table-barang tbody tr[data-row-id="'+id+'"]').remove();
	        set_total();
        });
    }

    function supplier_add() {
	    $.ajax({
		    url: '<?= $this->route->name('transaksi.pembelian.create_supplier') ?>',
		    success: function(response) {
			    bootbox.dialog({
				    title: '{{create}} {{supplier}}',
				    message: response
			    });
		    }
	    });
    }
</script>