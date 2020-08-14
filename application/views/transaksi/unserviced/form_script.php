<script>
    $(function () {
        $('#tanggal').inputmask('99-99-9999');
	    $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

	    $('#barang-barang-0').keydown(function (event) {
		    $('#barang-nama_barang-0').val($('#barang-barang-0').val());
		    if (event.keyCode == 13) {
			    $('#barang-satuan-0').val('').select();
			    return false;
		    }
	    });

	    $('#barang-satuan-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    $('#barang-jumlah-0').select();
			    return false;
		    }
	    });

	    $('#barang-jumlah-0').keydown(function (event) {
		    if (event.keyCode == 13) {
			    barang_add();
		    }
	    });

        $('#btn-add_barang').keydown(function (event) {
            if (event.keyCode == 13) {
                barang_add();
            }
        });

        $('#barang-barang-0').autocomplete({
            minLength: 3,
            delay: 1000,
            source: function(request, response) {
                $.ajax({
                    url: '<?= $this->route->name('transaksi.unserviced.get_barang_json') ?>?key='+$('#barang-barang-0').val(),
                    success: function(data) {
                        if (data.success) {
                            var barang = [];
                            $.each(data.data, function(i, row) {
                                barang.push({
                                    value : row.id_barang,
                                    label : row.nama_barang
                                });
                            });
                            response(barang);
                        }
                    }
                });
            },
            focus: function (event, ui) {
	            $('#barang-id_barang-0').val(ui.item.value);
	            $('#barang-nama_barang-0').val(ui.item.label);
                $('#barang-barang-0').val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
	            $('#barang-id_barang-0').val(ui.item.value);
	            $('#barang-nama_barang-0').val(ui.item.label);
	            $('#barang-satuan-0').val('');
                return false;
            }
        });

	    $('#barang-satuan-0').autocomplete({
		    minLength: 3,
		    delay: 1000,
		    source: function(request, response) {
			    $.ajax({
				    url: '<?= $this->route->name('transaksi.unserviced.get_satuan_json') ?>?key='+$('#barang-satuan-0').val()+'&id_barang='+$('#barang-id_barang-0').val()+'&barang='+$('#barang-nama_barang-0').val(),
				    success: function(data) {
					    if (data.success) {
						    var satuan = [];
						    $.each(data.data, function(i, row) {
							    satuan.push({
								    value : row.id_satuan,
								    label : row.satuan
							    });
						    });
						    response(satuan);
					    }
				    }
			    });
		    },
		    focus: function (event, ui) {
			    $('#barang-id_satuan-0').val(ui.item.value);
			    $('#barang-satuan-0').val(ui.item.label);
			    return false;
		    },
		    select: function (event, ui) {
			    $('#barang-id_satuan-0').val(ui.item.value);
			    $('#barang-satuan-0').val(ui.item.label);
			    return false;
		    }
	    });
    });

    function barang_add() {
	    $('#barang-barang-0').select();

        var no = new Date().getTime();
        var id_barang = $('#barang-id_barang-0').val();
        var nama_barang = $('#barang-nama_barang-0').val();
        var barang = $('#barang-barang-0').val();
        var id_satuan = $('#barang-id_satuan-0').val();
        var satuan = $('#barang-satuan-0').val();
        var jumlah = $('#barang-jumlah-0').val();

        if (nama_barang == '') {
            swal('{{barang_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_barang_harus_diisi}}');
            return false;
        }
        if ($('tr[data-row-id="'+no+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no+'">';
                html_row += '<td>';
                html_row += '<input type="hidden" name="unserviced_detail['+no+'][id_barang]" value="'+id_barang+'" id="barang-id_barang-'+no+'">';
                html_row += '<input type="hidden" name="unserviced_detail['+no+'][nama_barang]" value="'+nama_barang+'" id="barang-nama_barang-'+no+'">';
                html_row += barang;
                html_row += '</td>';
		        html_row += '<td>';
		        html_row += '<input type="hidden" name="unserviced_detail['+no+'][id_satuan]" value="'+id_barang+'" id="barang-id_satuan-'+no+'">';
		        html_row += '<input type="hidden" name="unserviced_detail['+no+'][satuan]" value="'+satuan+'" id="barang-satuan-'+no+'">';
	            html_row += satuan;
		        html_row += '</td>';
                html_row += '<td><input type="text" name="unserviced_detail['+no+'][jumlah]" value="'+jumlah+'" id="barang-jumlah-'+no+'" class="form-control input-sm text-center" onkeyup="barang_set_subtotal('+no+')" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove('+no+')"><i class="fa fa-trash"></i></button></td>';

            $('#table-barang tbody').append(html_row);
            $('#table-barang tbody tr[data-row-id]').buildForm();
            $('#barang-id_barang-0').val('');
            $('#barang-nama_barang-0').val('');
            $('#barang-barang-0').val('');
            $('#barang-id_satuan-0').val('');
	        $('#barang-satuan-0').val('');
            $('#barang-jumlah-0').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function barang_remove(id) {
        swalConfirm('{{confirm_pembelian_barang_delete}}', function() {
            $('#table-barang tbody tr[data-row-id="'+id+'"]').remove();
        });
    }
</script>