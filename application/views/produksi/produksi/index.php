<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{produksi}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?= $this->action->link('create', $this->url_generator->current_url().'/create', 'class="btn btn-primary"') ?>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                <thead>
                    <tr>
                        <th width="100">{{no_produksi}}</th>
                        <th width="100">{{tanggal_produksi}}</th>
                        <th width="100">{{barang}}</th>
                        <th width="150">{{nama}}</th>
                        <th width="100">{{satuan}}</th>
                        <th width="100">{{jumlah}}</th>
                        <th width="100">{{hpp}}</th>
                        <th>{{keterangan}}</th>
                        <td width="1"></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    var dataTable;
    $(function() {
        dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
	        autoWidth: false,
            searchDelay:1000,
	        scrollX: true,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'no_produksi', name: 'produksi.no_produksi'},
                {data: 'tanggal_produksi', name: 'produksi.tanggal_produksi'},
                {data: 'id_barang', name: 'produksi.id_barang', render: function (data, type, row) {
                    return row.kode_barang + ' - ' + row.nama_barang;
                }},
                {data: 'nama', name: 'barang_produksi.nama'},
                {data: 'satuan', name: 'satuan.satuan'},
                {data: 'jumlah', name: 'produksi.jumlah', class: 'text-center'},
                {data: 'hpp', name: 'produksi.hpp', class: 'text-right'},
                {data: 'keterangan', name: 'produksi.keterangan'},
                {data:'_action', searchable: false, orderable: false, class: 'text-center'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{produksi}}',
                    message: response
                });
            }
        });
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/delete/'+id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({message: response.message});
                        dataTable.ajax.reload();
                    } else {
	                    $.growl.error({message: response.message});
                    }
                }
            });
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>