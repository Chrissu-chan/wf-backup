<script>
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
        $('#form-add-pemakaian_barang-id_barang').val(data.id);
        $('#form-add-pemakaian_barang-kode_barang').val(data.kode);
        $('#form-add-pemakaian_barang-nama_barang').val(data.nama);
        $('#form-add-pemakaian_barang-barang').val(data.kode+' - '+data.nama);
        $('#form-add-pemakaian_barang-barang_id_satuan').val(data.id_satuan);
        $('#form-add-pemakaian_barang-barang_satuan').val(data.satuan);
        $.ajax({
            url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+data.id_satuan,
            success : function(response) {
                var html_option_id_satuan = '';
                html_option_id_satuan +='<option value="">{{pilih}}</option>';
                html_option_id_satuan +='<option value="'+data.id_satuan+'" selected>'+data.satuan+'</option>';
                $.each(response.data, function(key, row) {
                    html_option_id_satuan +='<option value="'+row.id+'">'+row.satuan+'</option>';
                });
                $('#form-add-pemakaian_barang-id_satuan').html(html_option_id_satuan);
            }
        });
    }

    function pemakaian_barang_add() {
        var id_barang = $('#form-add-pemakaian_barang-id_barang').val();
        var kode_barang = $('#form-add-pemakaian_barang-kode_barang').val();
        var nama_barang = $('#form-add-pemakaian_barang-nama_barang').val();
        var barang = $('#form-add-pemakaian_barang-barang').val();
        var id_satuan = $('#form-add-pemakaian_barang-id_satuan').val();
        var barang_id_satuan = $('#form-add-pemakaian_barang-barang_id_satuan').val();
        var barang_satuan = $('#form-add-pemakaian_barang-barang_satuan').val();
        var jumlah = $('#form-add-pemakaian_barang-jumlah').val();

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
                    html_row += '<input type="hidden" name="pemakaian_barang['+id_barang+'][id_barang]" value="'+id_barang+'" id="pemakaian_barang-id_barang-'+id_barang+'">';
                    html_row += '<input type="hidden" name="pemakaian_barang['+id_barang+'][kode_barang]" value="'+kode_barang+'" id="pemakaian_barang-kode_barang-'+id_barang+'">';
                    html_row += '<input type="hidden" name="pemakaian_barang['+id_barang+'][nama_barang]" value="'+nama_barang+'" id="pemakaian_barang-nama_barang-'+id_barang+'">';
                    html_row += barang;
                html_row += '</td>';
                html_row += '<td><select name="pemakaian_barang['+id_barang+'][id_satuan]" id="pemakaian_barang-id_satuan-'+id_barang+'" class="form-control input-sm"></select></td>';
                html_row += '<td>';
                    html_row += '<input type="hidden" name="pemakaian_barang['+id_barang+'][barang_id_satuan]" value="'+barang_id_satuan+'" id="pemakaian_barang-barang_id_satuan-'+id_barang+'">';
                    html_row += '<input type="hidden" name="pemakaian_barang['+id_barang+'][barang_satuan]" value="'+barang_satuan+'" id="pemakaian_barang-barang_satuan-'+id_barang+'">';
                    html_row += '<input type="text" name="pemakaian_barang['+id_barang+'][jumlah]" value="'+localization.number(jumlah, "")+'"  id="pemakaian_barang-jumlah-'+id_barang+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false">';
                html_row += '</td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="pemakaian_barang_remove('+id_barang+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-pemakaian_barang').before(html_row);
            $('#pemakaian_barang-id_satuan-'+id_barang).html($('#form-add-pemakaian_barang-id_satuan').html()).val(id_satuan);
            $('#table-pemakaian_barang tbody tr[data-row-id]').buildForm();
            $('#form-add-pemakaian_barang-id_barang').val('');
            $('#form-add-pemakaian_barang-kode_barang').val('');
            $('#form-add-pemakaian_barang-nama_barang').val('');
            $('#form-add-pemakaian_barang-barang').val('');
            $('#form-add-pemakaian_barang-barang_id_satuan').val('');
            $('#form-add-pemakaian_barang-barang_satuan').val('');
            $('#form-add-pemakaian_barang-id_satuan').html('<option value="">{{pilih}}</option>');
            $('#form-add-pemakaian_barang-jumlah').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function pemakaian_barang_remove(id_barang) {
        $('#table-pemakaian_barang tbody tr[data-row-id="'+id_barang+'"]').remove();
    }
</script>