<script>
	<?php if ($this->session->flashdata('print')) { ?>
		var win = window.open('<?= $this->route->name('transaksi.penjualan.nota', array('id' => $this->session->flashdata('print'))) ?>');
		setTimeout(function() {
			win.close();
		}, 5000);
	<?php } ?>

    $(function () {
        $('#tanggal').inputmask('99-99-9999');
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
                $('#barang-tuslah-0').select();
                return false;
            }
        });

        $('#barang-tuslah-0').keydown(function (event) {
            if (event.keyCode == 13) {
                barang_add();
                $('#barang-barang-0').select();
            }
        });

        $('#btn-add_barang').keydown(function (event) {
            if (event.keyCode == 13) {
                barang_add();
                $('#barang-barang-0').select();
            }
        });

        $('#barang-barang-0').keydown(function(event) {
            if (event.keyCode == 13) {
                $.ajax({
                    type: 'post',
                    data: 'key='+$('#barang-barang-0').val(),
                    url: '<?= $this->route->name('barang.barang.find_by_barcode_json') ?>',
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
                    url: '<?= $this->route->name('barang.barang.get_json') ?>?key='+$('#barang-barang-0').val(),
                    success: function(data) {
                        if (data.success) {
                            var barang = [];
                            $.each(data.data, function(i, row) {
                                barang.push({
	                                value : row.id,
                                    label : row.kode+' - '+row.barang,
	                                data : row
                                });
                            });
                            response(barang);
                        }
                    }
                });
            },
            focus: function (event, ui) {
                $('#barang-barang-0').val(ui.item.data.barang);
                return false;
            },
            select: function (event, ui) {
	            var data = ui.item.data;
                var barang = {
                    id_barang : data.id,
                    kode_barang : data.kode,
                    barang : data.barang,
	                jenis_barang : data.jenis
                };
                browse_barang_on_dblclick_callback(barang);
                return false;
            }
        });
    });

    function store() {
	    swalConfirm('{{confirm_penjualan_store}}', function() {
		    $('#frm-penjualan').submit();
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
			    barang_set_harga(0);
		    }
	    });
    }

    function barang_add() {
        var row_id = new Date().getTime();
        var id_barang = $('#barang-id_barang-0').val();
        var kode_barang = $('#barang-kode_barang-0').val();
        var nama_barang = $('#barang-nama_barang-0').val();
        var barang = $('#barang-barang-0').val();
        var jenis_barang = $('#barang-jenis_barang-0').val();
        var id_satuan = $('#barang-id_satuan-0').val();
        var barang_id_satuan = $('#barang-barang_id_satuan-0').val();
        var barang_satuan = $('#barang-barang_satuan-0').val();
        var jumlah = $('#barang-jumlah-0').val();
        var harga = $('#barang-harga-0').val();
        var diskon = $('#barang-diskon_rp-0').val();
        var diskon_persen = $('#barang-diskon_persen-0').val();
        var potongan = $('#barang-potongan-0').val();
        var subtotal = $('#barang-subtotal-0').val();
        var ppn = $('#barang-ppn_rp-0').val();
        var ppn_persen = $('#barang-ppn_persen-0').val();
        var tuslah = $('#barang-tuslah-0').val();
        var total = $('#barang-total-0').val();
        if (id_barang == '') {
            swal('{{barang_belum_diisi}}');
            return false;
        }
        if (id_satuan == '' && jenis_barang == 'barang') {
            swal('{{satuan_barang_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_barang_harus_diisi}}');
            return false;
        }
        if ($('tr[data-row-id="'+row_id+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+row_id+'">';
                html_row += '<td>';
                html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][id_produk]" value="'+id_barang+'" id="barang-id_barang-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][kode_produk]" value="'+kode_barang+'" id="barang-kode_barang-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][nama_produk]" value="'+nama_barang+'" id="barang-nama_barang-'+row_id+'">';
                html_row += barang;
                html_row += '</td>';
                html_row += '<td>';
                html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][barang_id_satuan]" value="'+barang_id_satuan+'" id="barang-barang_id_satuan-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][barang_satuan]" value="'+barang_satuan+'" id="barang-barang_satuan-'+row_id+'">';
                html_row += '<select name="penjualan_barang['+row_id+'][id_satuan]" id="barang-id_satuan-'+row_id+'" onchange="barang_set_harga('+row_id+')" class="form-control input-sm"></select>';
                html_row += '</td>';
                html_row += '<td><input type="text" name="penjualan_barang['+row_id+'][jumlah]" value="'+jumlah+'" id="barang-jumlah-'+row_id+'" onkeyup="barang_set_subtotal('+row_id+')" class="form-control input-sm text-center" onkeyup="barang_set_subtotal('+row_id+')" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_row += '<td><input type="text" name="penjualan_barang['+row_id+'][harga]" value="'+harga+'" id="barang-harga-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="barang_set_subtotal('+row_id+')" data-input-type="number-format" readonly></td>';
	            html_row += '<td>';
		        html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][diskon]" value="'+diskon+'" id="barang-diskon_rp-'+row_id+'">';
		        html_row += '<input type="text" name="penjualan_barang['+row_id+'][diskon_persen]" value="'+diskon_persen+'" id="barang-diskon_persen-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="barang_set_subtotal('+row_id+')" data-input-type="number-format" readonly>';
	            html_row += '</td>';
	            html_row += '<td><input type="text" name="penjualan_barang['+row_id+'][potongan]" value="'+potongan+'" id="barang-potongan-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="barang_set_subtotal('+row_id+')" data-input-type="number-format" readonly></td>';
                html_row += '<td><input type="text" name="penjualan_barang['+row_id+'][subtotal]" value="'+subtotal+'" id="barang-subtotal-'+row_id+'" class="form-control input-sm text-right" data-input-type="number-format" readonly></td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][ppn]" value="'+ppn+'" id="barang-ppn_rp-'+row_id+'">';
		        html_row += '<input type="hidden" name="penjualan_barang['+row_id+'][ppn_persen]" value="'+ppn_persen+'" id="barang-ppn_persen-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="barang_set_total('+row_id+')" data-input-type="number-format" readonly>';
		        html_row += '<input type="text" name="penjualan_barang['+row_id+'][tuslah]" value="'+tuslah+'" id="barang-tuslah-'+row_id+'" class="form-control input-sm text-right" onkeyup="barang_set_subtotal('+row_id+')" data-input-type="number-format">';
		        html_row += '</td>';
                html_row += '<td><input type="text" name="penjualan_barang['+row_id+'][total]" value="'+total+'" id="barang-total-'+row_id+'" class="form-control input-sm text-right" data-input-type="number-format" readonly></td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove('+row_id+')"><i class="fa fa-trash"></i></button></td>';
                html_row += '</tr>';
            $('#table-barang tbody').append(html_row);
            $('#barang-id_satuan-'+row_id).html($('#barang-id_satuan-0').html()).val(id_satuan);
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
            $('#barang-diskon_persen-0').val('');
            $('#barang-potongan-0').val('');
            $('#barang-subtotal-0').val('');
            $('#barang-ppn_rp-0').val('');
            $('#barang-ppn_persen-0').val('');
            $('#barang-tuslah-0').val('');
            $('#barang-total-0').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function barang_set_harga(id) {
	    var id_barang = $('#barang-id_barang-'+id).val();
	    var id_satuan = $('#barang-id_satuan-'+id).val();
	    $.ajax({
		    url: '<?= $this->route->name('master.obat.find_json') ?>?id_barang='+id_barang+'&id_satuan='+id_satuan,
		    success : function(response) {
			    if (response.success) {
				    var data = response.data;
				    $('#barang-jumlah-'+id).val(1);
				    $('#barang-harga-'+id).val(data.total);
				    barang_set_subtotal(id);
			    }
		    }
	    })
    }

    function barang_set_subtotal(id) {
        var jumlah = $('#barang-jumlah-'+id).val();
        var harga = $('#barang-harga-'+id).val();
        var diskon_persen = $('#barang-diskon_persen-'+id).val();
        var diskon = 0;
        var potongan = $('#barang-potongan-'+id).val();
        if (harga) {
            diskon = (toFloat(diskon_persen) / 100) * toFloat(harga);
            $('#barang-diskon_rp-'+id).val(diskon);
            $('#barang-subtotal-'+id).val(((toFloat(harga) - toFloat(diskon)) - toFloat(potongan)) * toFloat(jumlah));
        }
        barang_set_total(id);
    }

    function barang_set_total(id) {
        var subtotal = $('#barang-subtotal-'+id).val();
        var ppn_persen = $('#barang-ppn_persen-'+id).val();
        var ppn = (toFloat(subtotal) * toFloat(ppn_persen)) / 100;
        var tuslah = $('#barang-tuslah-'+id).val();
        if (subtotal) {
            $('#barang-ppn_rp-'+id).val(toFloat(ppn));
            $('#barang-total-'+id).val(toFloat(subtotal) + toFloat(ppn) + toFloat(tuslah));
        }
        set_subtotal();
    }

    function set_subtotal() {
        var subtotal = 0;
        $.each($("input[id*='barang-subtotal']"), function() {
            subtotal += toFloat($(this).val());
        });
        $('#subtotal').val(toFloat(subtotal));
        $('#label-subtotal').html(localization.number($('#subtotal').val()));
        set_ppn();
    }

    function set_ppn() {
        var ppn = 0;
        $.each($("input[id*='barang-ppn_rp']"), function() {
            ppn += toFloat($(this).val());
        });
        $('#ppn').val(toFloat(ppn));
        $('#label-ppn').html(localization.number($('#ppn').val()));
        set_tuslah();
    }

    function set_tuslah() {
        var tuslah = 0;
        $.each($("input[id*='barang-tuslah']"), function() {
            tuslah += toFloat($(this).val());
        });
        $('#tuslah').val(toFloat(tuslah));
        $('#label-tuslah').html(localization.number($('#tuslah').val()));
        set_total();
    }

    function set_diskon_persen() {
        var diskon_persen = $('#diskon_persen').val();
        $.each($('[data-row-id]'), function(i, elem) {
            var row_id = $(elem).data('row-id');
            if ($('#barang-diskon_persen-'+row_id, elem).is(':not([readonly])')) {
                $('#barang-diskon_persen-'+row_id, elem).val(diskon_persen);
            }
            barang_set_subtotal(row_id);
        });
    }

    function set_ppn_persen() {
        var ppn_persen = $('#ppn_persen').val();
        $.each($('[data-row-id]'), function(i, elem) {
            var row_id = $(elem).data('row-id');
            if ($('#barang-ppn_persen-'+row_id, elem).is(':not([readonly])')) {
                $('#barang-ppn_persen-'+row_id, elem).val(ppn_persen);
            }
            barang_set_subtotal(row_id);
        });
    }

    function set_total() {
        var subtotal = $('#subtotal').val();
        var ppn = $('#ppn').val();
        var tuslah = $('#tuslah').val();
	    $('#total').val(toFloat(subtotal) + toFloat(ppn) + toFloat(tuslah));
	    $('#label-total').html(localization.number($('#total').val()));
	    document.title = 'Total : '+localization.number($('#total').val());
        set_pembayaran();
    }

    function set_pembayaran() {
        var total = $('#total').val();
        if ($('#metode_pembayaran').val() == 'tunai') {
            $('#uang_muka').prop('disabled', true);
            $('#bayar').prop('disabled', false);
            var bayar = $('#bayar').val();
            if (toFloat(bayar) > toFloat(total)) {
                $('#kembali').val(toFloat(bayar) - toFloat(total));
            } else {
                $('#kembali').val(0);
            }
        } else {
	        $('#bayar').prop('disabled', true);
	        $('#uang_muka').prop('disabled', false);
            var uang_muka = $('#uang_muka').val();
            if (toFloat(uang_muka) > toFloat(total)) {
                $('#uang_muka').val(toFloat(total));
            }
        }

    }

    function set_metode_pembayaran() {
        if ($('#metode_pembayaran').val() == 'utang') {
            $('#uang_muka').prop('disabled', false);
            $('#bayar').prop('disabled', true);
            $('#bayar').val('');
            $('#jatuh_tempo').val('<?= date('d-m-Y', strtotime('+1 month')) ?>');
            $('#utang').show();
            $('#tunai').hide();
        } else {
            $('#uang_muka').prop('disabled', true);
            $('#bayar').prop('disabled', false);
            $('#jatuh_tempo').val('');
            $('#bayar').val('');
            $('#kembali').val('');
            $('#utang').hide();
            $('#tunai').show();
        }
    }

    function barang_remove(id) {
        swalConfirm('{{confirm_penjualan_barang_delete}}', function() {
            $('#table-barang tbody tr[data-row-id="'+id+'"]').remove();
            set_subtotal();
        });
    }

    function edit_harga(elem, id) {
        if ($(elem).is('[readonly]')) {
            var html = '<div class="form-group">';
            html += '<input type="password" name="password" id="password" class="form-control">';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<button type="button" class="btn btn-success" onclick="cek_password('+id+')">{{submit}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>';
            html += '</div>';

            bootbox.dialog({
                size: 'small',
                title: '{{password}}',
                message: html
            });
        }
    }

    function cek_password(id) {
        $.ajax({
            url: '<?= $this->route->name('transaksi.shift_aktif.check') ?>',
            type: 'post',
            data: 'password='+$('#password').val(),
            success: function (response) {
                if (response.success) {
                    $('#barang-harga-'+id).prop('readonly', false);
                    $('#barang-diskon_persen-'+id).prop('readonly', false);
                    $('#barang-potongan-'+id).prop('readonly', false);
                    $('#barang-ppn_persen-'+id).prop('readonly', false);
                    bootbox.hideAll();
                } else {
                    $.growl.error({message: response.message});
                }
            }
        });
    }

    function cancel() {
        bootbox.hideAll();
    }
</script>