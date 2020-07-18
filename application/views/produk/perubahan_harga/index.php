<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{perubahan_harga}}
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
            <table width="100%" id="data-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="1">{{perubahan_harga}}</th>
                        <th width="1">{{tanggal_mulai}}</th>
                        <th width="1">{{tanggal_selesai}}</th>
                        <th>{{keterangan}}</th>
                        <th width="1">{{aktif}}</th>
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
                {data: 'perubahan_harga', name: 'perubahan_harga.perubahan_harga', class: 'text-center nowrap'},
                {data: 'tanggal_mulai', name: 'perubahan_harga.tanggal_mulai', class: 'text-center nowrap'},
                {data: 'tanggal_selesai', name: 'perubahan_harga.tanggal_selesai', class: 'text-center nowrap'},
                {data: 'keterangan', name: 'perubahan_harga.keterangan'},
                {data: 'aktif', name: 'perubahan_harga.aktif', searchable: false, orderable: false, class: 'nowrap'},
                {data:'_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{perubahan_harga}}',
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

    function start(id) {
        swalConfirm('Aktifkan perubahan harga?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/start/'+id,
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

    function stop(id) {
        swalConfirm('Non aktifkan perubahan harga?}}', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/stop/'+id,
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