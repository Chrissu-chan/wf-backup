<script>
    function browse_barang_produksi() {
        $.ajax({
            url: '<?= $this->route->name('master.barang_produksi.browse') ?>',
            success: function(response) {
                 bootbox.dialog({
                    title: '{{browse}} {{barang_produksi}}',
                    message: response
                });
            }
        });
    }

    function browse_barang_produksi_on_dblclick_callback(data) {
        $('#detail_produksi').show();

        $('#id_barang_produksi').val(data.id);
        $('#id_barang').val(data.id_barang);
        $('#kode_barang').val(data.kode_barang);
        $('#nama_barang').val(data.nama_barang);
        $('#barang').val(data.kode_barang + ' - ' + data.nama_barang);
        $('#nama').val(data.nama);
        $('#id_satuan').val(data.id_satuan);
        $('#satuan').val(data.satuan);

        get_bahan_baku();
    }

    function get_bahan_baku() {
	    var id_barang_produksi = $('#id_barang_produksi').val();
        var jumlah = $('#jumlah').val();

        $.ajax({
            url: '<?= $this->route->name('master.barang_produksi.get_bahan_baku_json') ?>/'+id_barang_produksi,
            success : function(response) {
                var html_bahan_baku = '';
                var html_produksi_bahan_baku = '';
                $.each(response.data, function(key, row) {
                    html_bahan_baku += '<tr data-row-id="'+row.id_barang+'">';
                    html_bahan_baku += '<td>';
                    html_bahan_baku += '<input type="hidden" name="bahan_baku['+row.id_barang+'][id_barang]" value="'+row.id_barang+'" id="bahan_baku-id_barang-'+row.id_barang+'">';
                    html_bahan_baku += '<input type="hidden" name="bahan_baku['+row.id_barang+'][kode_barang]" value="'+row.kode_barang+'" id="bahan_baku-kode_barang-'+row.id_barang+'">';
                    html_bahan_baku += '<input type="hidden" name="bahan_baku['+row.id_barang+'][nama_barang]" value="'+row.nama_barang+'" id="bahan_baku-nama_barang-'+row.id_barang+'">';
                    html_bahan_baku += row.kode_barang+' - '+row.nama_barang;
                    html_bahan_baku += '</td>';
                    html_bahan_baku += '<td>';
                    html_bahan_baku += '<input type="hidden" name="bahan_baku['+row.id_barang+'][satuan]" value="'+row.satuan+'" id="bahan_baku-satuan-'+row.id_barang+'">';
                    html_bahan_baku += row.satuan;
                    html_bahan_baku += '</td>';
                    html_bahan_baku += '<td class="text-center">';
                    html_bahan_baku += '<input type="hidden" name="bahan_baku['+row.id_barang+'][jumlah]" value="'+row.jumlah+'"  id="bahan_baku-jumlah-'+row.id_barang+'">';
                    html_bahan_baku += row.jumlah;
                    html_bahan_baku += '</td>';
                    html_bahan_baku += '</tr>';

                    html_produksi_bahan_baku += '<tr>';
                    html_produksi_bahan_baku += '<td>';
                    html_produksi_bahan_baku += '<input type="hidden" name="produksi_bahan_baku['+row.id_barang+'][id_barang]" value="'+row.id_barang+'" id="bahan_baku-id_barang-'+row.id_barang+'">';
                    html_produksi_bahan_baku += '<input type="hidden" name="produksi_bahan_baku['+row.id_barang+'][kode_barang]" value="'+row.kode_barang+'" id="bahan_baku-kode_barang-'+row.id_barang+'">';
                    html_produksi_bahan_baku += '<input type="hidden" name="produksi_bahan_baku['+row.id_barang+'][nama_barang]" value="'+row.nama_barang+'" id="bahan_baku-nama_barang-'+row.id_barang+'">';
                    html_produksi_bahan_baku += row.kode_barang+' - '+row.nama_barang;
                    html_produksi_bahan_baku += '</td>';
                    html_produksi_bahan_baku += '<td>';
                    html_produksi_bahan_baku += '<input type="hidden" name="produksi_bahan_baku['+row.id_barang+'][barang_id_satuan]" value="'+row.barang_id_satuan+'" id="produksi_bahan_baku-barang_id_satuan-'+id_barang+'">';
                    html_produksi_bahan_baku += '<input type="hidden" name="produksi_bahan_baku['+row.id_barang+'][barang_satuan]" value="'+row.barang_satuan+'" id="produksi_bahan_baku-barang_satuan-'+id_barang+'">';
                    html_produksi_bahan_baku += '<select name="produksi_bahan_baku['+row.id_barang+'][id_satuan]" id="produksi_bahan_baku-id_satuan-'+row.id_barang+'" class="form-control input-sm" onchange="get_hpp('+row.id_barang+')"></select></td>';
                    html_produksi_bahan_baku += '</td>';
                    html_produksi_bahan_baku += '<td><input type="text" name="produksi_bahan_baku['+row.id_barang+'][jumlah]" value="'+(row.jumlah * jumlah)+'" id="produksi_bahan_baku-jumlah-'+row.id_barang+'" class="form-control input-sm text-center" onkeyup="get_hpp('+row.id_barang+')" data-input-type="number-format" data-thousand-separator="false"></td>';
                    html_produksi_bahan_baku += '<td><input type="text" name="produksi_bahan_baku['+row.id_barang+'][hpp]" value="0" id="produksi_bahan_baku-hpp-'+row.id_barang+'" class="form-control input-sm text-right" data-input-type="number-format" data-thousand-separator="false" readonly></td>';
                    html_produksi_bahan_baku += '<td><input type="text" name="produksi_bahan_baku['+row.id_barang+'][total]" value="0" id="produksi_bahan_baku-total-'+row.id_barang+'" class="form-control input-sm text-right" data-input-type="number-format" data-thousand-separator="false" readonly></td>';
                    html_produksi_bahan_baku += '</tr>';

                    $.ajax({
                        url: '<?= $this->route->name('master.satuan.get_json') ?>?id='+row.barang_id_satuan,
                        success : function(response_satuan) {
                            var html_option_id_satuan = '';
                            html_option_id_satuan +='<option value="">{{pilih}}</option>';
                            html_option_id_satuan +='<option value="'+row.barang_id_satuan+'" '+(row.barang_id_satuan == row.id_satuan ? 'selected':'')+'>'+row.barang_satuan+'</option>';
                            $.each(response_satuan.data, function(key, val) {
                                html_option_id_satuan +='<option value="'+val.id+'" '+(val.id == row.id_satuan ? 'selected':'')+'>'+val.satuan+'</option>';
                            });
                            $('#produksi_bahan_baku-id_satuan-'+row.id_barang).html(html_option_id_satuan);
                        },
                        complete : function() {
                            get_hpp(row.id_barang);
                        }
                    });
                });

                $('#table-barang_produksi_bahan_baku tbody').html(html_bahan_baku);
                $('#table-produksi_bahan_baku tbody').html(html_produksi_bahan_baku).buildForm();
            }
        });
    }

    function get_hpp(id_barang) {
        var id_satuan = $('#produksi_bahan_baku-id_satuan-'+id_barang).val();
        var jumlah = $('#produksi_bahan_baku-jumlah-'+id_barang).val();

        $.ajax({
            url: '<?= $this->route->name('master.barang.find_hpp_json') ?>/'+id_barang+'?id_satuan='+id_satuan+'&id_gudang=<?= $this->cabang_gudang_m->scope('aktif_cabang')->first()->id_gudang ?>',
            success : function(response) {
                if (response.success) {
                    $('#produksi_bahan_baku-hpp-'+id_barang).val((response.data.hpp));
                    $('#produksi_bahan_baku-total-'+id_barang).val((response.data.hpp * jumlah));
                } else {
                    alert(response.message);
                }
            },
            complete : function() {
                total_biaya();
            }
        });
    }

    function total_biaya() {
        var jumlah = toFloat($('#jumlah').val());
        var total_biaya_lainnya = toFloat($('#total_biaya_lainnya').val());

        var total_bahan_baku = 0;
        $.each($("input[id*='produksi_bahan_baku-total']"), function() {
            total_bahan_baku += toFloat($(this).val());
        });
        $('#total_bahan_baku').val(total_bahan_baku);
        $('#total_biaya_produksi').val(toFloat(total_bahan_baku + total_biaya_lainnya));
        $('#hpp').val(toFloat(total_bahan_baku + total_biaya_lainnya) / jumlah);
    }
</script>