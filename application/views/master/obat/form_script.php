<script>
    $(function() {
	    /*$('#kode').keyup(function() {
		    $('#barcode').val($(this).val());
	    });*/

        $("input:checkbox").on('click', function() {
            var $box = $(this);
            if ($box.is(":checked")) {
                var group = "input:checkbox[name*='satuan_beli']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
        });

        $('#hna').keyup(function() {
            var hna = toFloat($('#hna').val());
	        var ppn_persen = toFloat($('#ppn_persen').val());
            var hpp = (100 / (100 + ppn_persen)) * hna;
            $('#hpp').val(hpp);
            set_total();
        });
    });

    function set_hpp() {
        var total = toFloat($('#total').val());
	    var diskon_persen = toFloat($('#diskon_persen').val());
	    var hna = total;
	    if (diskon_persen > 0) {
		    hna = (100 / (100 - diskon_persen)) * hna;
	    }
        var ppn_persen = toFloat($('#ppn_persen').val());
        var hpp = (100 / (100 + ppn_persen)) * hna;
        $('#hna').val(hna);
        $('#hpp').val(hpp);
    }

    function set_hna() {
        var hpp = toFloat($('#hpp').val());
	    var ppn_persen = toFloat($('#ppn_persen').val());
	    var ppn = (ppn_persen / 100) * hpp;
	    $('#ppn').val(ppn);
        $('#hna').val(hpp + ppn);
        set_total();
    }

    function set_total() {
        var hna = toFloat($('#hna').val());
	    var diskon_persen = toFloat($('#diskon_persen').val());
	    var diskon = (diskon_persen / 100) * hna;
	    $('#diskon').val(diskon);
        $('#total').val(hna - diskon);
    }
    
    function satuan_barang_add() {
	    var key = new Date().getTime();
	    var satuan = $('#form-add-satuan_barang-satuan').val();
	    var konversi = $('#form-add-satuan_barang-konversi').val();
	    var satuan_beli = $('#form-add-satuan_barang-satuan_beli').is(':checked');
	    if (satuan == '') {
		    swal('{{satuan_belum_diisi}}');
		    return false;
	    }
	    if (konversi == '') {
		    swal('{{konversi_belum_diisi}}');
		    return false;
	    }
	    if ($('tr[data-row-id="'+key+'"]').length == 0) {
		    var html_row = '<tr data-row-id="'+key+'">';
            html_row += '<td>';
		    html_row += '<input type="text" name="satuan_barang['+key+'][satuan]" value="'+satuan+'" id="satuan_barang-satuan-'+key+'" class="form-control input-sm">';
		    html_row += '</td>';
            html_row += '<td><input type="text" name="satuan_barang['+key+'][konversi]" value="'+konversi+'" id="satuan_barang-konversi-'+key+'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"></td>';
            html_row += '<td><input type="checkbox" name="satuan_barang['+key+'][satuan_beli]" value="1" id="satuan_barang-satuan_beli-'+key+'" class="form-control" '+(satuan_beli ? 'checked' : '')+'></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="satuan_barang_remove('+key+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
		    $('#table-satuan_barang tbody').append(html_row);
		    $('#form-add-satuan_barang-satuan').val('');
		    $('#form-add-satuan_barang-konversi').val('');
		    $('#form-add-satuan_barang-satuan_beli').prop('checked', false);
	    }
    }
    
    function satuan_barang_remove(key) {
	    swalConfirm('{{confirm_satuan_barang_delete}}', function() {
		    $('#table-satuan_barang tbody tr[data-row-id="'+key+'"]').remove();
	    });
    }
</script>