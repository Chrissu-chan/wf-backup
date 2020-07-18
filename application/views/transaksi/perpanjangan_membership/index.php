<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{perpanjangan_membership}}
        <div class="pull-right">
            <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
        </div>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th>{{kode_member}}</th>
                        <th>{{jenis_member}}</th>
                        <th>{{nama_member}}</th>
                        <th>{{telepon}}</th>
                        <th>{{hp}}</th>
                        <th>{{alamat}}</th>
                        <th>{{tanggal_daftar}}</th>
                        <th>{{tanggal_expired}}</th>
                        <th>{{tanggal_expired_setelah_perpanjangan}}</th>
                        <td></td>
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
                {data: 'kode', name: 'member.kode'},
                {data: 'id_jenis_member', name: 'member.id_jenis_member'},
                {data: 'id_pelanggan', name: 'member.id_pelanggan'},
                {data: 'tanggal_daftar', name: 'member.tanggal_daftar'},
                {data: 'tanggal_expired', name: 'member.tanggal_expired'},
                {data: 'created_by', name: 'member.created_by'},
                {data: 'created_at', name: 'member.created_at'},
                {data: 'updated_by', name: 'member.updated_by'},
                {data: 'updated_by', name: 'member.updated_by'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{perpanjangan_membership}}',
                    message: response
                });
            }
        });
    }

    function create() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create',
            success: function(response) {
                bootbox.dialog({
                    title: '{{create}} {{perpanjangan_membership}}',
                    message: response
                });
            }
        });
    }

    function cancel() {
        bootbox.hideAll();
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>