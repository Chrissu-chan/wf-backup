<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{shift}}
        <div class="pull-right">
            <?= $this->action->link('create', $this->url_generator->current_url().'/create', 'class="btn btn-primary"') ?>
        </div>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th width="200">{{shift}}</th>
                        <th>{{jumlah_shift}}</th>
                        <td width="1"></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        var dataTable;
        $('#data-table').dataTable({
            processing : true,
            serverSide : true,
            ajax : '<?= $this->route->name('master.shift') ?>',
            columns : [
                {data : 'shift'},
                {data : 'waktu', render : function(data, type, row) {
                    var html = '';
                    $.each(data, function(i, waktu) {
                        html += '<p>'+waktu.shift_waktu + ' ('+waktu.jam_mulai+' - '+waktu.jam_selesai+')</p>';
                    });
                    return html;
                }, searchable : false, orderable : false},
                {data : '_action', searchable : false, orderable : false, class : 'text-right nowrap'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{shift}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>