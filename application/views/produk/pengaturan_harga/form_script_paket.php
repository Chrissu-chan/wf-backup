<script>
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
        if (jumlah <= '') {
            swal('{{jumlah_harus_lebih_dari_0}}');
            return false;
        }
        if (harga <= 0) {
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

    function harga_remove(no) {
        $('#table-harga tbody tr[data-row-id="'+no+'"]').remove();
    }

    function harga_cabang_add(id_cabang) {
        if ($('#cabang_harga-id_cabang-'+id_cabang).prop('checked')) {
            $('#harga_cabang-'+id_cabang).show();
        } else {
            $('#harga_cabang-'+id_cabang).hide();
        }
    }

    function cabang_harga_cabang_add(id_cabang) {
        var no = new Date().getTime();
        var utama = 0;
        var urutan = $('#form-add-cabang_harga_cabang-urutan-'+id_cabang).val();
        var jumlah = $('#form-add-cabang_harga_cabang-jumlah-'+id_cabang).val();
        var harga = $('#form-add-cabang_harga_cabang-harga-'+id_cabang).val();

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
        if ($('#table-cabang_harga_cabang-'+id_cabang+' tbody tr[data-row-id="'+no+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+no+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="cabang_harga_cabang['+id_cabang+']['+no+'][utama]" value="0" id="cabang_harga_cabang-utama-'+id_cabang+'-'+no+'">';
            html_row += '<input type="text" name="cabang_harga_cabang['+id_cabang+']['+no+'][urutan]" value="'+urutan+'" id="cabang_harga_cabang-urutan-'+id_cabang+'-'+no+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0">';
            html_row += '</td>';
            html_row += '<td><input type="text" name="cabang_harga_cabang['+id_cabang+']['+no+'][jumlah]" value="'+jumlah+'" id="cabang_harga_cabang-jumlah-'+id_cabang+'-'+no+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="text" name="cabang_harga_cabang['+id_cabang+']['+no+'][harga]" value="'+harga+'" id="cabang_harga_cabang-harga-'+id_cabang+'-'+no+'" class="form-control input-sm text-right" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_cabang_remove('+id_cabang+','+no+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-cabang_harga_cabang-'+id_cabang).before(html_row);
            $('#table-cabang_harga_cabang-'+id_cabang+' tbody tr[data-row-id]').buildForm();

            $('#form-add-cabang_harga_cabang-urutan-'+id_cabang).val('');
            $('#form-add-cabang_harga_cabang-jumlah-'+id_cabang).val('');
            $('#form-add-cabang_harga_cabang-harga-'+id_cabang).val('');
        } else {
            swal('{{harga_satuan_sudah_ada_di_daftar}}');
        }
    }

    function cabang_harga_cabang_remove(id_cabang, no) {
        $('#table-cabang_harga_cabang-'+id_cabang+' tbody tr[data-row-id="'+no+'"]').remove();
    }
</script>