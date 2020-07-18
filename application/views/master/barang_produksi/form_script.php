<script>
	$(function () {
		$('#id_barang').change(function () {
			$.ajax({
				url: '<?= $this->route->name('master.barang.find_json') ?>?id='+$(this).val(),
				success : function(response) {
					var data = response.data;
					$('#barang_id_satuan').val(data.id_satuan);
					$('#satuan').val(data.satuan);
					$.ajax({
						url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+data.id_satuan,
						success : function(response) {
							var html_option_id_satuan = '';
							html_option_id_satuan +='<option value="0">{{pilih}}</option>';
							html_option_id_satuan +='<option value="'+data.id_satuan+'" selected>'+data.satuan+'</option>';
							$.each(response.data, function(key, row) {
								html_option_id_satuan+='<option value="'+row.id+'">'+row.satuan+'</option>';
							});
							$('#id_satuan').html(html_option_id_satuan);
						}
					});
				}
			});
		})
	});

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
        $('#form-add-bahan_baku-id_barang').val(data.id);
        $('#form-add-bahan_baku-kode_barang').val(data.kode);
        $('#form-add-bahan_baku-nama_barang').val(data.nama);
        $('#form-add-bahan_baku-barang').val(data.kode+' - '+data.nama);
        $('#form-add-bahan_baku-barang_id_satuan').val(data.id_satuan);
        $('#form-add-bahan_baku-barang_satuan').val(data.satuan);
        $.ajax({
            url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+data.id_satuan,
            success : function(response) {
                var html_option_id_satuan = '';
                html_option_id_satuan +='<option value="">{{pilih}}</option>';
                html_option_id_satuan +='<option value="'+data.id_satuan+'" selected>'+data.satuan+'</option>';
                $.each(response.data, function(key, row) {
                    html_option_id_satuan +='<option value="'+row.id+'">'+row.satuan+'</option>';
                });
                $('#form-add-bahan_baku-id_satuan').html(html_option_id_satuan);
            }
        });
    }

    function bahan_baku_add() {
        var id_barang = $('#form-add-bahan_baku-id_barang').val();
        var kode_barang = $('#form-add-bahan_baku-kode_barang').val();
        var nama_barang = $('#form-add-bahan_baku-nama_barang').val();
        var barang = $('#form-add-bahan_baku-barang').val();
        var id_satuan = $('#form-add-bahan_baku-id_satuan').val();
        var barang_id_satuan = $('#form-add-bahan_baku-barang_id_satuan').val();
        var barang_satuan = $('#form-add-bahan_baku-barang_satuan').val();
        var jumlah = $('#form-add-bahan_baku-jumlah').val();

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
        if ($('tr[data-row-id="'+id_barang+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+id_barang+'">';
                html_row += '<td>';
                    html_row += '<input type="hidden" name="bahan_baku['+id_barang+'][id_barang]" value="'+id_barang+'" id="bahan_baku-id_barang-'+id_barang+'">';
                    html_row += '<input type="hidden" name="bahan_baku['+id_barang+'][kode_barang]" value="'+kode_barang+'" id="bahan_baku-kode_barang-'+id_barang+'">';
                    html_row += '<input type="hidden" name="bahan_baku['+id_barang+'][nama_barang]" value="'+nama_barang+'" id="bahan_baku-nama_barang-'+id_barang+'">';
                    html_row += barang;
                html_row += '</td>';
                html_row += '<td><select name="bahan_baku['+id_barang+'][id_satuan]" id="bahan_baku-id_satuan-'+id_barang+'" class="form-control input-sm"></select></td>';
                html_row += '<td>';
                    html_row += '<input type="hidden" name="bahan_baku['+id_barang+'][barang_id_satuan]" value="'+barang_id_satuan+'" id="bahan_baku-barang_id_satuan-'+id_barang+'">';
                    html_row += '<input type="hidden" name="bahan_baku['+id_barang+'][barang_satuan]" value="'+barang_satuan+'" id="bahan_baku-barang_satuan-'+id_barang+'">';
                    html_row += '<input type="text" name="bahan_baku['+id_barang+'][jumlah]" value="'+jumlah+'"  id="bahan_baku-jumlah-'+id_barang+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false">';
                html_row += '</td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="bahan_baku_remove('+id_barang+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-bahan_baku').before(html_row);
            $('#bahan_baku-id_satuan-'+id_barang).html($('#form-add-bahan_baku-id_satuan').html()).val(id_satuan);
            $('#table-bahan_baku tbody tr[data-row-id]').buildForm();
            $('#form-add-bahan_baku-id_barang').val('');
            $('#form-add-bahan_baku-kode_barang').val('');
            $('#form-add-bahan_baku-nama_barang').val('');
            $('#form-add-bahan_baku-barang').val('');
            $('#form-add-bahan_baku-barang_id_satuan').val('');
            $('#form-add-bahan_baku-barang_satuan').val('');
            $('#form-add-bahan_baku-id_satuan').html('<option value="">{{pilih}}</option>');
            $('#form-add-bahan_baku-jumlah').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function bahan_baku_remove(id_barang) {
        $('#table-bahan_baku tbody tr[data-row-id="'+id_barang+'"]').remove();
    }
</script>