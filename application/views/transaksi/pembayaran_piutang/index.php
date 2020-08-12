<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{pembayaran_piutang}}
        <div class="pull-right">
            <?= $this->action->link('create', $this->route->name('transaksi.pembayaran_piutang.create'), 'class="btn btn-primary"') ?>
        </div>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                <thead>
                    <tr>
                        <th>{{no_piutang}}</th>
                        <th width="150">{{tanggal_bayar}}</th>
                        <th width="150" class="text-right">{{jumlah_bayar}}</th>
                        <th width="150">{{no_ref_pembayaran}}</th>
                        <th width="1">{{status}}</th>
                        <th width="1"></th>
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
            searchDelay:1000,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'no_piutang', name: 'piutang.no_piutang'},
                {data: 'tanggal_bayar', name: 'pembayaran_piutang.tanggal_bayar'},
                {data: 'jumlah_bayar', name: 'pembayaran_piutang.jumlah_bayar', class: 'text-right'},
                {data: 'no_ref_pembayaran', name: 'pembayaran_piutang.no_ref_pembayaran'},
                {data: 'batal', name: 'pembayaran_piutang.batal'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });
    });

    function store() {
        $('.validation-message').remove();
        var form = $('#frm-create')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/store',
            type: 'post',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType:false,
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
	                $.growl.error({message: response.message});
                    $.each(response.validation, function(key, message) {
                        $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                    });
                }
            }
        });
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan membatalkan transaksi ini?', function() {
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

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{upload_file}} {{pembayaran_piutang}}',
                    message: response
                });
            }
        });
    }

    function cancel() {
        bootbox.hideAll();
    }

    function upload(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/upload/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{upload_file}} {{pembayaran_piutang}}',
                    message: response
                });
            }
        });
    }

</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>