<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{utang}}
        <div class="pull-right">
            <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
        </div>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table id="data-table" class="table table-bordered table-condensed nowrap" width="100%">
                <thead>
                    <tr>
                        <th>{{no_utang}}</th>
                        <th>{{jenis_utang}}</th>
                        <th>{{no_refrensi}}</th>
                        <th>{{nama}}</th>
                        <th>{{tanggal_utang}}</th>
                        <th>{{tanggal_jatuh_tempo}}</th>
                        <th>{{jumlah_utang}}</th>
                        <th>{{sisa_utang}}</th>
                        <th width="1">{{lunas}}</th>
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
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'no_utang', name: 'utang.no_utang'},
                {data: 'jenis_utang', name: 'utang.jenis_utang'},
                {data: 'no_ref', name: 'utang.no_ref'},
                {data: 'nama', name: 'utang.nama'},
                {data: 'tanggal_utang', name: 'utang.tanggal_utang'},
                {data: 'tanggal_jatuh_tempo', name: 'utang.tanggal_jatuh_tempo'},
                {data: 'jumlah_utang', name: 'utang.jumlah_utang', class: 'text-right'},
                {data: 'sisa_utang', name: 'utang.sisa_utang', class: 'text-right'},
                {data: 'lunas', name: 'utang.lunas', class: 'text-center'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });
    });

    function create() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create',
            success: function(response) {
                bootbox.dialog({
                    title: '{{create}} {{utang}}',
                    message: response
                });
            }
        });
    }

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

    function edit(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/edit/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{edit}} {{utang}}',
                    message: response
                });
            }
        });
    }

    function update(id) {
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/'+id,
            type: 'post',
            data: $('#frm-edit').serialize(),
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

    function cancel() {
        bootbox.hideAll();
    }

    function upload(id) {
	    $.ajax({
		    url: '<?= $this->url_generator->current_url() ?>/upload/'+id,
		    success: function(response) {
			    bootbox.dialog({
				    title: '{{upload_file}} {{utang}}',
				    message: response
			    });
		    }
	    });
    }

    function bayar(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/bayar/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{bayar}} {{utang}}',
                    message: response
                });
            }
        });
    }

    function pelunasan(id) {
        swalConfirm('Apakah anda yakin akan melunasi data utang ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/pelunasan/'+id,
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

    function batal_pelunasan(id) {
        swalConfirm('Apakah anda yakin akan membatalkan pelunasan data utang ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/batal_pelunasan/'+id,
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