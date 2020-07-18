<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">{{mapping_rak}}</h1>
        </div>
        <div class="col-md-6 text-right">
	        <div class="form-group">
		        <button type="button" id="btn-store" class="btn btn-success">{{store}}</button>
	        </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h1 class="panel-title">{{dari}}</h1>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>{{gudang}}</label>
                        <?= $this->form->select('filter_gudang_dari', lists($this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->view('cabang_gudang')->get(), 'id', 'gudang', $this->localization->lang('select')), null, 'id="filter-gudang_dari" class="form-control" data-input-type="select2"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{rak}}</label>
                        <?= $this->form->select('filter_rak_dari', array('' => $this->localization->lang('tanpa_rak')), null, 'id="filter-rak_dari" class="form-control" data-input-type="select2"') ?>
                    </div>
                    <?= $this->form->open(NULL, 'id="frm-dari"') ?>
                    <table width="100%" id="tbl-dari" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th width="1"><input type="checkbox" id="select-all"></th>
                                <th width="100">{{kode}}</th>
                                <th>{{nama}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <?= $this->form->close() ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h1 class="panel-title">{{ke}}</h1>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>{{gudang}}</label>
                        <?= $this->form->select('filter_gudang_ke', lists($this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->view('cabang_gudang')->get(), 'id', 'gudang', $this->localization->lang('select')), null, 'id="filter-gudang_ke" class="form-control" data-input-type="select2"') ?>
                    </div>
                    <div class="form-group">
                        <label>{{rak}}</label>
                        <?= $this->form->select('filter_rak_ke', array('' => $this->localization->lang('tanpa_rak')), null, 'id="filter-rak_ke" class="form-control" data-input-type="select2"') ?>
                    </div>
                    <?= $this->form->open($this->route->name('inventory.mapping_rak.store'), 'id="frm-ke"') ?>
                    <table width="100%" id="tbl-ke" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th width="100">{{kode}}</th>
                                <th>{{nama}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <?= $this->form->close() ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        $('#filter-gudang_dari').change(function() {
            get_rak_gudang_dari($(this).val());
        });

        $('#filter-rak_dari').change(function() {
            get_barang_dari();
        });

        $('#filter-gudang_ke').change(function() {
            get_rak_gudang_ke($(this).val());
        });

        $('#filter-rak_ke').change(function() {
            get_barang_ke();
        });

        $('#select-all').click(function(){
            $('input[type="checkbox"]:not(:disabled)', $('#tbl-dari tbody')).prop('checked', this.checked);
        });

        $('#btn-store').click(function() {
            $.ajax({
                url : '<?= $this->route->name('inventory.mapping_rak.store') ?>',
                type : 'post',
                data : 'id_rak_gudang='+$('#filter-rak_ke').val()+
                    '&'+$('#frm-dari').serialize(),
                success : function (response) {
                    if (response.success) {
                        $.growl.notice({message: response.message});
                        get_barang_dari();
                        get_barang_ke();
                    } else {
                        $.growl.error({message: response.message});
                    }
                }
            })
        })
    });

    function get_rak_gudang_dari(id_gudang) {
        $.ajax({
            url: '<?= $this->route->name('inventory.rak_gudang.get_json') ?>?id_gudang='+id_gudang,
            success: function(response) {
                var data = [];
                data.push({
                    id: '0',
                    text : '{{tanpa_rak}}'
                });
                if (response.data) {
                    $.each(response.data, function(key, row) {
                        data.push({
                            id: row.id,
                            text: row.rak
                        });
                    });
                }
                $('#filter-rak_dari').html('').select2({
                    data: data
                }).change();
            }
        });
    }

    function get_barang_dari() {
        $.ajax({
            url: '<?= $this->route->name('master.barang.get_json') ?>?id_rak_gudang='+$('#filter-rak_dari').val(),
            success: function(response) {
                var html_row = '';
                if (response.data) {
                    $.each(response.data, function(index, row) {
                        html_row += '<tr>';
                        html_row += '<td><input type="checkbox" name="id_barang[]" value="'+row.id+'"></td>';
                        html_row += '<td>'+row.kode+'</td>';
                        html_row += '<td>'+row.nama+'</td>';
                        html_row += '</tr>';
                    })
                }
                $('#tbl-dari tbody').html(html_row);
            }
        });
    }

    function get_rak_gudang_ke(id_gudang) {
        $.ajax({
            url: '<?= $this->route->name('inventory.rak_gudang.get_json') ?>?id_gudang='+id_gudang,
            success: function(response) {
                var data = [];
                data.push({
                    id: '0',
                    text : '{{tanpa_rak}}'
                });
                if (response.data) {
                    $.each(response.data, function(key, row) {
                        data.push({
                            id: row.id,
                            text: row.rak
                        });
                    });
                }
                $('#filter-rak_ke').html('').select2({
                    data: data
                }).change();
            }
        });
    }

    function get_barang_ke() {
        $.ajax({
            url: '<?= $this->route->name('master.barang.get_json') ?>?id_rak_gudang='+$('#filter-rak_ke').val(),
            success: function(response) {
                var html_row = '';
                if (response.data) {
                    $.each(response.data, function(index, row) {
                        html_row += '<tr>';
                        html_row += '<td>'+row.kode+'</td>';
                        html_row += '<td>'+row.nama+'</td>';
                        html_row += '</tr>';
                    })
                }
                $('#tbl-ke tbody').html(html_row);
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>