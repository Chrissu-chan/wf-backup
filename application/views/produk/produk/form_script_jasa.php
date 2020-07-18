<script>
    $(function() {
        $('#jasa').change(function() {
            if (this.value) {
                $('#produk').val($('#jasa option:selected').text());
            } else {
                $('#produk').val('');
            }
        })
    });

    function cabang_add(id) {
        if ($('#cabang-cabang-'+id).prop('checked')) {
            var html_komisi = '<tr id="form-add-komisi_petugas-'+ id +'">';
            html_komisi += '<td>';
            html_komisi += '<input type="hidden" value="'+id+'" id="form-add-komisi_petugas-id_cabang-'+id+'">';
            html_komisi += '<select id="form-add-komisi_petugas-id_petugas-'+id+'" class="form-control input-sm">';
            html_komisi += '<option value="0">{{semua_petugas}}</option>';
            $.ajax({
                url: '<?= $this->route->name('master.petugas.get_json') ?>?id_cabang='+id,
                success : function(response) {
                    $.each(response.data, function(key, row) {
                        $('#form-add-komisi_petugas-id_petugas-'+id).append($('<option></option>').attr('value', row.id).text(row.nama));
                    });
                }
            });
            html_komisi += '</select>';
            html_komisi += '</td>';
            html_komisi += '<td><input type="text" value="" id="form-add-komisi_petugas-komisi-'+id+'" class="form-control input-sm text-center" data-input-type="number-format"></td>';
            html_komisi += '<td><button type="button" class="btn btn-primary btn-sm" onclick="komisi_add('+id+')"><i class="fa fa-plus"></i></button></td>';
            html_komisi += '</tr>';

            $('#table-komisi_petugas-'+id+' tbody').html(html_komisi).buildForm();
        } else {
            $('#table-komisi_petugas-'+id+' tbody').html('');
        }
    }

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
        if (jumlah == '') {
            swal('{{jumlah_belum_diisi}}');
            return false;
        }
        if (harga == '') {
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

    function komisi_add(id) {
        var id_cabang = $('#form-add-komisi_petugas-id_cabang-'+id).val();
        var id_petugas = $('#form-add-komisi_petugas-id_petugas-'+id).val();
        var petugas = $('#form-add-komisi_petugas-id_petugas-'+id+' option:selected').text();
        var komisi = $('#form-add-komisi_petugas-komisi-'+id).val();

        if (komisi == '') {
            swal('{{komisi_belum_diisi}}');
            return false;
        }

        if ($('#table-komisi_petugas-'+id+' tbody tr[data-row-id="'+id_petugas+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+id_petugas+'">';
            html_row += '<td>';
            html_row += '<input type="hidden" name="komisi_petugas['+id+']['+id_petugas+'][id_cabang]" value="'+id_cabang+'" id="komisi_petugas-id_cabang-'+id+'-'+id_petugas+'">';
            html_row += '<input type="hidden" name="komisi_petugas['+id+']['+id_petugas+'][id_petugas]" value="'+id_petugas+'" id="komisi_petugas-id_petugas-'+id+'-'+id_petugas+'">';
            html_row += '<input type="hidden" name="komisi_petugas['+id+']['+id_petugas+'][petugas]" value="'+petugas+'" id="komisi_petugas-petugas-'+id+'-'+id_petugas+'">';
            html_row += petugas;
            html_row += '</td>';
            html_row += '<td><input type="text" name="komisi_petugas['+id+']['+id_petugas+'][komisi]" value="'+komisi+'" id="komisi_petugas-komisi-'+id+'-'+id_petugas+'" class="form-control input-sm text-center" data-input-type="number-format"></td>';
            html_row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="komisi_remove('+id_cabang+','+id_petugas+')"><i class="fa fa-trash"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-komisi_petugas-'+id).before(html_row);
            $('#table-komisi_petugas-'+id+' tbody tr[data-row-id]').buildForm();

            $('#form-add-komisi_petugas-id_petugas-'+id).val('').change();
            $('#form-add-komisi_petugas-komisi-'+id).val('');

            no++;
        } else {
            swal('{{komisi_petugas_sudah_ada_di_daftar}}');
        }
    }

    function harga_remove(no) {
        $('#table-harga tbody tr[data-row-id="'+no+'"]').remove();
    }

    function komisi_remove(id_cabang, id_petugas) {
        $('#table-komisi_petugas-'+id_cabang+' tbody tr[data-row-id="'+id_petugas+'"]').remove();
    }

</script>