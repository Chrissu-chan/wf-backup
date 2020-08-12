<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{monitoring_shift}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
	        <div class="form-inline">
		        <div class="form-group">
			        <label>{{tanggal}}</label>
			        <div class="input-group input-group-sm">
				        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				        <?= $this->form->date('filter_tanggal', date('d-m-Y'), 'id="filter-tanggal" class="form-control" data-input-type="datepicker"') ?>
			        </div>
		        </div>
		        <div class="form-group">
			        <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
		        </div>
	        </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="1" class="nowrap">{{tanggal}}</th>
                        <th width="200">{{shift}}</th>
                        <th>{{kasir}}</th>
                        <th width="150" class="text-right">{{uang_awal}}</th>
                        <th width="150" class="text-right">{{uang_akhir}}</th>
                        <th width="1">{{active}}</th>
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
            searchDelay:1000,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'created_at', name: 'shift_aktif.created_at', class : 'nowrap'},
                {data: 'shift_waktu', name: 'shift_waktu.shift_waktu'},
                {data: 'name', name: 'users.name'},
                {data: 'uang_awal', name: 'shift_aktif_kasir.uang_awal', searchable: false, orderable: false, class: 'text-right nowrap'},
                {data: 'uang_akhir', name: 'shift_aktif_kasir.uang_akhir', searchable: false, orderable: false, class: 'text-right nowrap'},
                {data: 'active', name: 'shift_aktif_kasir.active', searchable: false, orderable: false, class: 'text-center'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ],
	        order: [[0, 'DESC']]
        });

	    $('#btn-filter').click(function() {
		    dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?tanggal=' + $('#filter-tanggal').val()).load();
	    });
    });
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>