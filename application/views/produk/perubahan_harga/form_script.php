<script>
    var dataTable;
    $(function() {
        dataTable = $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= $this->route->name('produk.perubahan_harga.get_produk') ?>',
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

        $('#perubahan_harga_kondisi-harga-operation').change(function () {
            if ($(this).val() == '=') {
                $('#perubahan_harga_kondisi-harga-to').prop('readonly', false);
            } else {
                $('#perubahan_harga_kondisi-harga-to').prop('readonly', true).val('');
            }
        });

        $('#permanen').change(function () {
            if (this.checked) {
                $('#tanggal_mulai').val('<?= date('d-m-Y') ?>').prop('readonly', true);
                $('#tanggal_selesai').val('').prop('readonly', true);
            } else {
                $('#tanggal_mulai').prop('readonly', false);
                $('#tanggal_selesai').prop('readonly', false);
            }
        });
    });

    function checked_attribute(param) {
        if ($('#perubahan_harga_kondisi-'+param+'-column').is(':checked')) {
            $('#perubahan_harga_kondisi-'+param+'-operation').prop('disabled', false);
            $('#perubahan_harga_kondisi-'+param+'-from').prop('disabled', false);
            $('#perubahan_harga_kondisi-'+param+'-to').prop('disabled', false);
        } else {
            $('#perubahan_harga_kondisi-'+param+'-operation').prop('disabled', true);
            $('#perubahan_harga_kondisi-'+param+'-from').prop('disabled', true).val('').change();
            $('#perubahan_harga_kondisi-'+param+'-to').prop('disabled', true).val('');
        }
    }
</script>