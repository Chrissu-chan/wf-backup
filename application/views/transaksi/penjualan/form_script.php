<script>
	<?php if ($this->session->flashdata('print')) { ?>
		var win = window.open('<?= $this->route->name('transaksi.penjualan.nota', array('id' => $this->session->flashdata('print'))) ?>');
		setTimeout(function() {
			win.close();
		}, 5000);
	<?php } ?>

    $(function () {
	    document.title = 'Penjualan Total : 0';

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $('#produk-id_satuan-0').keydown(function (event) {
            if (event.keyCode == 13) {
                $('#produk-jumlah-0').select();
                return false;
            }
        });

        $('#produk-jumlah-0').keydown(function (event) {
            if (event.keyCode == 13) {
                $('#produk-tuslah-0').select();
                return false;
            }
        });

        $('#produk-tuslah-0').keydown(function (event) {
            if (event.keyCode == 13) {
                produk_add();
            }
        });

        $('#btn-add_produk').keydown(function (event) {
            if (event.keyCode == 13) {
                produk_add();
            }
        });

        $('#produk-produk-0').keydown(function(event) {
            if (event.keyCode == 13) {
                $.ajax({
                    type: 'post',
                    data: 'key='+$('#produk-produk-0').val(),
                    url: '<?= $this->route->name('produk.produk.find_by_barcode_json') ?>',
                    success: function(response) {
                        if (response.success) {
                            browse_produk_on_dblclick_callback(response.data);
                        } else {
                            $('#produk-id_produk-0').val('');
                            $('#produk-kode_produk-0').val('');
                            $('#produk-nama_produk-0').val('');
                            $('#produk-jenis_produk-0').val('');
                            $('#produk-jumlah-0').val(0);
	                        $('#produk-id_satuan-0').html('<option value="0">{{pilih}}</option>');
                        }
                    }
                });
            }
        });

        $('#produk-produk-0').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '<?= $this->route->name('produk.produk.get_json') ?>?key='+$('#produk-produk-0').val(),
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
                $('#produk-produk-0').val(ui.item.data.produk);
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

    function store() {
	    swalConfirm('{{confirm_penjualan_store}}', function() {
		    $('#frm-penjualan').submit();
	    });
    }

    function browse_produk() {
        $.ajax({
            url: '<?= $this->route->name('produk.produk.browse') ?>?tanggal_mutasi='+$('#tanggal').val(),
            success: function(response) {
                 bootbox.dialog({
	                 title: '{{browse}} {{produk}}',
	                 size: 'large',
	                 message: response
                });
            }
        });
    }

    function browse_produk_on_dblclick_callback(data) {
	    if ($('tr[data-row-id="'+data.id_produk+'"]').length == 0) {
		    $('#produk-id_produk-0').val(data.id_produk);
		    $('#produk-kode_produk-0').val(data.kode_produk);
		    $('#produk-nama_produk-0').val(data.produk);
		    $('#produk-jenis_produk-0').val(data.jenis_produk);
		    $('#produk-produk-0').val(data.produk);
		    $('#produk-jumlah-0').val(1);
		    $.ajax({
			    url: '<?= $this->route->name('produk.pengaturan_harga.harga_satuan_json') ?>?id_produk='+data.id_produk,
			    success: function (response) {
				    var html_option_id_satuan='';
				    html_option_id_satuan+='<option value="0">{{pilih}}</option>';
				    $.each(response.data, function (key, row) {
					    if (row.utama) {
						    $('#produk-satuan-0').val(row.satuan);
						    html_option_id_satuan+='<option value="'+row.id_satuan+'" selected>'+row.satuan+'</option>';
					    } else {
						    html_option_id_satuan+='<option value="'+row.id_satuan+'">'+row.satuan+'</option>';
					    }
				    });
				    $('#produk-id_satuan-0').html(html_option_id_satuan).focus();
				    produk_set_harga(0);
			    }
		    });
	    } else {
		    swal('{{produk_sudah_ada_di_daftar}}');
	    }
    }

    function produk_add() {
	    $('#produk-produk-0').select();

        //var row_id = new Date().getTime();
        var id_produk = $('#produk-id_produk-0').val();
	    var row_id = id_produk;
        var kode_produk = $('#produk-kode_produk-0').val();
        var nama_produk = $('#produk-nama_produk-0').val();
        var produk = $('#produk-produk-0').val();
        var jenis_produk = $('#produk-jenis_produk-0').val();
        var id_satuan = $('#produk-id_satuan-0').val();
        var satuan = $('#produk-satuan-0').val();
        var jumlah = $('#produk-jumlah-0').val();
        var harga = $('#produk-harga-0').val();
        var diskon = $('#produk-diskon_rp-0').val();
        var diskon_persen = $('#produk-diskon_persen-0').val();
        var potongan = $('#produk-potongan-0').val();
        var subtotal = $('#produk-subtotal-0').val();
        var ppn = $('#produk-ppn_rp-0').val();
        var ppn_persen = $('#produk-ppn_persen-0').val();
        var tuslah = $('#produk-tuslah-0').val();
        var total = $('#produk-total-0').val();
        if (id_produk == '') {
            swal('{{produk_belum_diisi}}');
            return false;
        }
        if (id_satuan == '' && jenis_produk == 'barang') {
            swal('{{satuan_produk_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_produk_harus_diisi}}');
            return false;
        }
        if ($('tr[data-row-id="'+row_id+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+row_id+'">';
                html_row += '<td>';
                html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][id_produk]" value="'+id_produk+'" id="produk-id_produk-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][kode_produk]" value="'+kode_produk+'" id="produk-kode_produk-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][nama_produk]" value="'+nama_produk+'" id="produk-nama_produk-'+row_id+'">';
                html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][jenis_produk]" value="'+jenis_produk+'" id="produk-jenis_produk-'+row_id+'">';
                html_row += produk;
                html_row += '</td>';
                html_row += '<td>';
                html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][satuan]" value="'+satuan+'" id="produk-satuan-'+row_id+'">';
                html_row += '<select name="penjualan_produk['+row_id+'][id_satuan]" id="produk-id_satuan-'+row_id+'" onchange="produk_set_harga('+row_id+')" class="form-control input-sm"></select>';
                html_row += '</td>';
                html_row += '<td><input type="text" name="penjualan_produk['+row_id+'][jumlah]" value="'+jumlah+'" id="produk-jumlah-'+row_id+'" onkeyup="produk_set_harga('+row_id+')" class="form-control input-sm text-center" onkeyup="produk_set_subtotal('+row_id+')" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_row += '<td><input type="text" name="penjualan_produk['+row_id+'][harga]" value="'+harga+'" id="produk-harga-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="produk_set_subtotal('+row_id+')" data-input-type="number-format" readonly></td>';
	            html_row += '<td>';
		        html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][diskon]" value="'+diskon+'" id="produk-diskon_rp-'+row_id+'">';
		        html_row += '<input type="text" name="penjualan_produk['+row_id+'][diskon_persen]" value="'+diskon_persen+'" id="produk-diskon_persen-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="produk_set_subtotal('+row_id+')" data-input-type="number-format" readonly>';
	            html_row += '</td>';
	            html_row += '<td><input type="text" name="penjualan_produk['+row_id+'][potongan]" value="'+potongan+'" id="produk-potongan-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="produk_set_subtotal('+row_id+')" data-input-type="number-format" readonly></td>';
                html_row += '<td><input type="text" name="penjualan_produk['+row_id+'][subtotal]" value="'+subtotal+'" id="produk-subtotal-'+row_id+'" class="form-control input-sm text-right" data-input-type="number-format" readonly></td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][ppn]" value="'+ppn+'" id="produk-ppn_rp-'+row_id+'">';
		        html_row += '<input type="hidden" name="penjualan_produk['+row_id+'][ppn_persen]" value="'+ppn_persen+'" id="produk-ppn_persen-'+row_id+'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '+row_id+')" onkeyup="produk_set_total('+row_id+')" data-input-type="number-format" readonly>';
		        html_row += '<input type="text" name="penjualan_produk['+row_id+'][tuslah]" value="'+tuslah+'" id="produk-tuslah-'+row_id+'" class="form-control input-sm text-right" onkeyup="produk_set_subtotal('+row_id+')" data-input-type="number-format">';
		        html_row += '</td>';
                html_row += '<td><input type="text" name="penjualan_produk['+row_id+'][total]" value="'+total+'" id="produk-total-'+row_id+'" class="form-control input-sm text-right" data-input-type="number-format" readonly></td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="produk_remove('+row_id+')"><i class="fa fa-trash"></i></button></td>';
                html_row += '</tr>';
            $('#table-produk tbody').append(html_row);
            $('#produk-id_satuan-'+row_id).html($('#produk-id_satuan-0').html()).val(id_satuan);
            $('#table-produk tbody tr[data-row-id]').buildForm();
            $('#produk-id_produk-0').val('');
            $('#produk-kode_produk-0').val('');
            $('#produk-nama_produk-0').val('');
            $('#produk-produk-0').val('');
            $('#produk-produk_id_satuan-0').val('');
            $('#produk-produk_satuan-0').val('');
            $('#produk-id_satuan-0').html('<option value="0">{{pilih}}</option>');
            $('#produk-jumlah-0').val('');
            $('#produk-harga-0').val('');
            $('#produk-diskon_rp-0').val('');
            $('#produk-diskon_persen-0').val('');
            $('#produk-potongan-0').val('');
            $('#produk-subtotal-0').val('');
            $('#produk-ppn_rp-0').val('');
            $('#produk-ppn_persen-0').val('');
            $('#produk-tuslah-0').val('');
            $('#produk-total-0').val('');
        } else {
            swal('{{produk_sudah_ada_di_daftar}}');
        }
    }

    function produk_set_harga(id) {
	    $('#produk-satuan-'+id).val($('#produk-id_satuan-'+id+' option:selected').text());
        $.ajax({
            url: '<?= $this->route->name('produk.pengaturan_harga.find_harga_satuan_json') ?>?id_produk='+$('#produk-id_produk-'+id).val()+'&id_satuan='+$('#produk-id_satuan-'+id).val()+'&jumlah='+parseInt($('#produk-jumlah-'+id).val()),
            success : function(response) {
                if (response.success) {
                    var data = response.data;
                    var harga = data.harga;
                    $.ajax({
                        url: '<?= $this->route->name('produk.perubahan_harga.find_json') ?>?id_produk='+data.id_produk+'&id_satuan='+data.id_satuan,
                        success: function (response) {
                            if (response.success) {
                                var perubahan_harga = response.data;
                                harga = parseInt(((perubahan_harga.perubahan_harga * data.harga) / 100)) + parseInt(data.harga);
                            }
                        }
                    }).done(function () {
                        $('#produk-harga-'+id).val(harga);
                        $('#produk-ppn_persen-'+id).val(data.ppn_persen);
                        produk_set_subtotal(id);
                    });

                    $.ajax({
                        url: '<?= $this->route->name('produk.diskon.find_json') ?>?id_produk='+data.id_produk+'&id_satuan='+data.id_satuan,
                        success: function (response) {
                            if (response.success) {
                                var diskon = response.data;
                                $('#produk-diskon_persen-'+id).val(diskon.diskon);
                                $('#produk-potongan-'+id).val(diskon.potongan);
                            }
                        }
                    }).done(function() {
                        produk_set_subtotal(id);
                    });
                } else {
                    $('#produk-harga-'+id).val(0);
                    $('#produk-diskon_persen-'+id).val(0);
                    $('#produk-potongan-'+id).val(0);
                    $('#produk-ppn_persen-'+id).val(0);
                    produk_set_subtotal(id);
                }
            }
        });
    }

    function produk_set_subtotal(id) {
        var jumlah = $('#produk-jumlah-'+id).val();
        var harga = $('#produk-harga-'+id).val();
        var diskon_persen = $('#produk-diskon_persen-'+id).val();
        var diskon = 0;
        var potongan = $('#produk-potongan-'+id).val();
        if (harga) {
            diskon = (toFloat(diskon_persen) / 100) * toFloat(harga);
            $('#produk-diskon_rp-'+id).val(diskon);
            $('#produk-subtotal-'+id).val(((toFloat(harga) - toFloat(diskon)) - toFloat(potongan)) * toFloat(jumlah));
        }
        produk_set_total(id);
    }

    function produk_set_total(id) {
        var subtotal = $('#produk-subtotal-'+id).val();
        var ppn_persen = $('#produk-ppn_persen-'+id).val();
        var ppn = (toFloat(subtotal) * toFloat(ppn_persen)) / 100;
        var tuslah = $('#produk-tuslah-'+id).val();
        if (subtotal) {
            $('#produk-ppn_rp-'+id).val(toFloat(ppn));
            $('#produk-total-'+id).val(toFloat(subtotal) + toFloat(ppn) + toFloat(tuslah));
        }
        set_subtotal();
    }

    function set_subtotal() {
        var subtotal = 0;
        $.each($("input[id*='produk-subtotal']"), function() {
            subtotal += toFloat($(this).val());
        });
        $('#subtotal').val(toFloat(subtotal));
        $('#label-subtotal').html(localization.number($('#subtotal').val()));
        set_ppn();
    }

    function set_ppn() {
        var ppn = 0;
        $.each($("input[id*='produk-ppn_rp']"), function() {
            ppn += toFloat($(this).val());
        });
        $('#ppn').val(toFloat(ppn));
        $('#label-ppn').html(localization.number($('#ppn').val()));
        set_tuslah();
    }

    function set_tuslah() {
        var tuslah = 0;
        $.each($("input[id*='produk-tuslah']"), function() {
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
            if ($('#produk-diskon_persen-'+row_id, elem).is(':not([readonly])')) {
                $('#produk-diskon_persen-'+row_id, elem).val(diskon_persen);
            }
            produk_set_subtotal(row_id);
        });
    }

    function set_ppn_persen() {
        var ppn_persen = $('#ppn_persen').val();
        $.each($('[data-row-id]'), function(i, elem) {
            var row_id = $(elem).data('row-id');
            if ($('#produk-ppn_persen-'+row_id, elem).is(':not([readonly])')) {
                $('#produk-ppn_persen-'+row_id, elem).val(ppn_persen);
            }
            produk_set_subtotal(row_id);
        });
    }

    function set_total() {
        var subtotal = $('#subtotal').val();
        var ppn = $('#ppn').val();
        var tuslah = $('#tuslah').val();
	    $('#total').val(toFloat(subtotal) + toFloat(ppn) + toFloat(tuslah));
	    $('#label-total').html(localization.number($('#total').val()));
	    document.title = 'Penjualan Total : '+localization.number($('#total').val());
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

    function produk_remove(id) {
        swalConfirm('{{confirm_penjualan_produk_delete}}', function() {
            $('#table-produk tbody tr[data-row-id="'+id+'"]').remove();
            set_subtotal();
        });
    }

    function edit_harga(elem, id) {
        if ($(elem).is('[readonly]')) {
            var html = '<div class="form-group">';
            html += '<label>{{username}}</label>';
            html += '<input type="text" name="username" id="username" class="form-control">';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<div class="form-group">';
            html += '<label>{{password}}</label>';
            html += '<input type="password" name="password" id="password" class="form-control">';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<button type="button" class="btn btn-success" onclick="cek_password('+id+')">{{submit}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>';
            html += '</div>';

            bootbox.dialog({
                size: 'small',
                title: '<i class="fa fa-lock"></i>',
                message: html
            });
        }
    }

    function cek_password(id) {
        $.ajax({
            url: '<?= $this->route->name('transaksi.penjualan.check_permission_diskon') ?>',
            type: 'post',
            data: 'username='+$('#username').val()+'&password='+$('#password').val(),
            success: function (response) {
                if (response.success) {
                    $('#produk-harga-'+id).prop('readonly', false);
                    $('#produk-diskon_persen-'+id).prop('readonly', false);
                    $('#produk-potongan-'+id).prop('readonly', false);
                    $('#produk-ppn_persen-'+id).prop('readonly', false);
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