<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{barang_stock_opname}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table id="data-table" class="table table-bordered table-condensed  nowrap">
                <thead>
                    <tr>
                        <th width="1">#</th>
                        <th width="150">{{kode}}</th>
                        <th width="150">{{barcode}}</th>
                        <th>{{nama}}</th>
                        <th width="150">{{satuan_barang}}</th>
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
	        searchDelay: 1500,
            scrollX: true,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data : 'id', searchable : false, orderable : false, render : function(data, type, row) {
                    return '<input type="checkbox" name="id_barang[]" value="'+data+'" id="id_barang-'+data+'" onclick="update('+data+')" '+(row.stock_opname_barang ? 'checked' : '')+' />';
                }},
                {data: 'kode', name: 'barang.kode'},
                {data: 'barcode', name: 'barang.barcode'},
                {data: 'nama', name: 'barang.nama'},
                {data: 'satuan', name: 'satuan.satuan'}
            ]
        });
    });

    function update(id) {
        var method = 'insert';
        if (!$('#id_barang-'+id).is(':checked')) {
            method = 'delete'
        }
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/'+id+'/'+method,
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    dataTable.ajax.reload();
                } else {
	                $.growl.error({message: response.message});
                }
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>