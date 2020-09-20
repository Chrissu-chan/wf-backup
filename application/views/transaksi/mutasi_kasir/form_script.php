<script>
    $(function() {
        $('#tipe').change(function() {
            var html = '';
            if ($(this).val() == 'pemasukan') {
                html += '<label>{{jenis_transaksi}}</label>';
                html += '<select name="id_jenis_transaksi" id="id_jenis_transaksi" class="form-control" data-input-type="select2">';
                html += '<option value="">{{select}}</option>';
                <?php foreach ($this->jenis_transaksi_m->scope('pemasukan')->get() as $jenis_transaksi) { ?>
                    html += '<option value="<?= $jenis_transaksi->id ?>"><?= $jenis_transaksi->jenis_transaksi ?></option>';
                <?php } ?>
                html += '</select>';
            } else if ($(this).val() == 'pengeluaran') {
                html += '<label>{{jenis_transaksi}}</label>';
                html += '<select name="id_jenis_transaksi" id="id_jenis_transaksi" class="form-control" data-input-type="select2">';
                html += '<option value="">{{select}}</option>';
                <?php foreach ($this->jenis_transaksi_m->scope('pengeluaran')->get() as $jenis_transaksi) { ?>
                    html += '<option value="<?= $jenis_transaksi->id ?>"><?= $jenis_transaksi->jenis_transaksi ?></option>';
                <?php } ?>
                html += '</select>';
            }
            $('#form-group-jenis_transaksi').html(html);
            $('#form-group-jenis_transaksi').buildForm();
        });
    });

    function choose_file() {
        $('#file_path').click();
    }

    function select_file() {
        $('#file').val($('#file_path').val().split('\\').pop());
    }

    function delete_file() {
        $('#file_path').val('');
        $('#file').val('');
    }
</script>