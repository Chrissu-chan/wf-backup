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
</script>