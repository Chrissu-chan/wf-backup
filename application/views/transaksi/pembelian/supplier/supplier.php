<?= $this->form->open(null, 'id="frm-create"') ?>
<div id="frm-message"></div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label>{{kategori_supplier}}</label>
			<?= $this->form->select('id_kategori_supplier', tree_lists($this->kategori_supplier_m->get(), 'id', 'parent_id', 0, 'id', 'kategori_supplier'), null, 'class="form-control"') ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>{{jenis_supplier}}</label>
			<?= $this->form->select('id_jenis_supplier', lists($this->jenis_supplier_m->get(), 'id', 'jenis_supplier'), null, 'class="form-control"') ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label>{{supplier}}</label>
			<?= $this->form->text('supplier', null, 'id="supplier" class="form-control"') ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>{{cabang}}</label>
			<?= $this->form->select('supplier_cabang[]', lists($this->cabang_m->scope('auth')->get(), 'id', 'nama'), null, 'id="supplier_cabang" class="form-control" data-input-type="select2" multiple') ?>
		</div>
	</div>
</div>
<div class="form-group">
	<label>{{nama_pemilik}}</label>
	<?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{jenis_kelamin}}</label>
	<?= $this->form->select('jenis_kelamin', $this->supplier_m->enum('jenis_kelamin'), null, 'class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{telepon}}</label>
	<?= $this->form->text('telepon', null, 'id="telepon" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{kota}}</label>
	<?= $this->form->select('id_kota', lists($this->kota_m->get(), 'id', 'kota', 'pilih_kota'), null, 'id="id_kota" class="form-control" data-input-type="select2" style="width:100%"') ?>
</div>
<div class="form-group">
	<label>{{alamat}}</label>
	<?= $this->form->textarea('alamat', null, 'id="alamat" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{bank}}</label>
	<?= $this->form->select('id_bank', lists($this->bank_m->get(), 'id', 'bank', 'pilih_bank'), null, 'class="form-control"') ?>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label>{{no_rekening}}</label>
			<?= $this->form->text('no_rekening', null, 'id="no_rekening" class="form-control"') ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>{{nama_rekening}}</label>
			<?= $this->form->text('nama_rekening', null, 'id="nama_rekening" class="form-control"') ?>
		</div>
	</div>
</div>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="store_supplier()">{{store}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>

<script>
    $(function() {
        $('#frm-create').buildForm();
    });

    function store_supplier() {
	    $('.validation-message').remove();
	    $.ajax({
		    url: '<?= $this->route->name('supplier.supplier.store') ?>',
		    type: 'post',
		    data: $('#frm-create').serialize(),
		    success: function(response) {
			    if (response.success) {
				    $.growl.notice({message: response.message});
				    bootbox.hideAll();
			    } else {
				    $.growl.error({message: response.message});
				    $.each(response.validation, function(key, message) {
					    $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
				    });
			    }
		    }
	    });
    }

    function cancel() {
	    bootbox.hideAll();
    }
</script>