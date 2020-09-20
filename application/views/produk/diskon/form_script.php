<script>
    var dataTable;
    $(function() {
        dataTable = $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= $this->route->name('produk.diskon.get_produk') ?>',
            columns:[
                {data : 'id', searchable : false, orderable : false, render : function(data, type, row) {
                    return '<input type="checkbox" name="produk[]" value="'+data+'" />';
                }},
                {data: 'kode', name: 'produk.kode'},
                {data: 'barcode', name: 'produk.barcode'},
                {data: 'produk', name: 'produk.produk'},
                {data: 'jenis', name: 'produk.jenis'}
            ]
        });
        
        $('#select_all').click(function(){
            var rows = dataTable.rows().nodes();
            $('input[type="checkbox"]:not(:disabled)', rows).prop('checked', this.checked);
        });

        $('#diskon_kondisi-harga-operation').change(function () {
            if ($(this).val() == '=') {
                $('#diskon_kondisi-harga-to').prop('readonly', false);
            } else {
                $('#diskon_kondisi-harga-to').prop('readonly', true).val('');
            }
        });
    });

    function checked_attribute(param) {
        if ($('#diskon_kondisi-'+param+'-column').is(':checked')) {
            $('#diskon_kondisi-'+param+'-operation').prop('disabled', false);
            $('#diskon_kondisi-'+param+'-from').prop('disabled', false);
            $('#diskon_kondisi-'+param+'-to').prop('disabled', false);
        } else {
            $('#diskon_kondisi-'+param+'-operation').prop('disabled', true);
            $('#diskon_kondisi-'+param+'-from').prop('disabled', true).val('').change();
            $('#diskon_kondisi-'+param+'-to').prop('disabled', true).val('');
        }
    }
</script>