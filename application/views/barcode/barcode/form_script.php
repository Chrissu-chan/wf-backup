<script>
    $(function() {
        $('#frm-barcode').submit(function() {
            $.ajax({
                url: '<?= $this->route->name('barcode.barcode.print_barcode') ?>',
                type: 'post',
                data: $('#frm-barcode').serialize(),
                success: function (response) {
                    if (response.success) {
                        window.open('<?= $this->route->name('barcode.barcode.view_barcode') ?>/'+response.file_name,'popUpWindow','width=794, height=1123, left=50, top=50, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');
                    } else {
                        $.growl.notice({message: response.message});
                    }
                }
            });
            return false;
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
        $('#barang-id_barang-0').val(data.id);
        $('#barang-kode_barang-0').val(data.kode);
        $('#barang-nama_barang-0').val(data.nama);
        $('#barang-barcode-0').val(data.barcode);
        $('#barang-barang-0').val(data.kode+' - '+data.nama);
    }

    function barang_add() {
        var id_barang = $('#barang-id_barang-0').val();
        var kode_barang = $('#barang-kode_barang-0').val();
        var nama_barang = $('#barang-nama_barang-0').val();
        var barcode = $('#barang-barcode-0').val();
        var barang = $('#barang-barang-0').val();
        var jumlah = $('#barang-jumlah-0').val();

        if (id_barang == '') {
            swal('{{barang_belum_diisi}}');
            return false;
        }
        if (jumlah == '') {
            swal('{{jumlah_barang_harus_diisi}}');
            return false;
        }
        if ($('tr[data-row-id="'+id_barang+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+id_barang+'">';
                html_row += '<td>';
                html_row += '<input type="hidden" name="barang['+id_barang+'][id_barang]" value="'+id_barang+'" id="barang-id_barang-'+id_barang+'">';
                html_row += '<input type="hidden" name="barang['+id_barang+'][kode_barang]" value="'+kode_barang+'" id="barang-kode_barang-'+id_barang+'">';
                html_row += '<input type="hidden" name="barang['+id_barang+'][nama_barang]" value="'+nama_barang+'" id="barang-nama_barang-'+id_barang+'">';
                html_row += '<input type="hidden" name="barang['+id_barang+'][barcode]" value="'+barcode+'" id="barang-barcode-'+id_barang+'">';
                html_row += barang;
                html_row += '</td>';
                html_row += '<td>'+barcode+'</td>';
                html_row += '<td><input type="text" name="barang['+id_barang+'][jumlah]" value="'+jumlah+'" id="barang-jumlah-'+id_barang+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"></td>';
                html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove('+id_barang+')"><i class="fa fa-trash"></i></button></td>';
                html_row += '</tr>';

            $('#form-add-barang').before(html_row);
            $('#table-barang tbody tr[data-row-id]').buildForm();
            $('#barang-id_barang-0').val('');
            $('#barang-kode_barang-0').val('');
            $('#barang-nama_barang-0').val('');
            $('#barang-barcode-0').val('');
            $('#barang-barang-0').val('');
            $('#barang-jumlah-0').val('');
        } else {
            swal('{{barang_sudah_ada_di_daftar}}');
        }
    }

    function barang_remove(id_barang) {
        $('#table-barang tbody tr[data-row-id="'+id_barang+'"]').remove();
    }
</script>