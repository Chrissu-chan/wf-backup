<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{perpanjangan_member}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?= $this->action->link('create', $this->route->name('member.perpanjangan.create'), 'class="btn btn-primary"') ?>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th>{{nama}}</th>
                        <th width="85">{{jenis_identitas}}</th>
                        <th width="95">{{no_identitas}}</th>
                        <th width="85">{{jenis_kelamin}}</th>
                        <th width="95">{{telepon}}</th>
                        <th width="95">{{hp}}</th>
                        <th width="95">{{kota}}</th>
                        <td width="30"></td>
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
                {data: 'nama', name: 'pelanggan.nama'},
                {data: 'jenis_identitas', name: 'pelanggan.jenis_identitas'},
                {data: 'no_identitas', name: 'pelanggan.no_identitas'},
                {data: 'jenis_kelamin', name: 'pelanggan.jenis_kelamin'},
                {data: 'telepon', name: 'pelanggan.telepon'},
                {data: 'hp', name: 'pelanggan.hp'},
                {data: 'kota', name: 'pelanggan.kota'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{perpajangan_member}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>