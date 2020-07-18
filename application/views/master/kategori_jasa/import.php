<?= $this->form->open_multipart($this->route->name('master.kategori_jasa.import_store'), 'id="frm-import"') ?>
<div class="form-group">
    <div class="input-group input-group-sm">
        <?= $this->form->text('file', null, 'id="file" class="form-control"  onclick="choose_file()" readonly') ?>
        <?= $this->form->upload('file', null, 'id="kategori_jasa" onchange="select_file()" style="display: none;"') ?>
        <span class="input-group-btn">
    		<button type="button" class="btn btn-default" onclick="choose_file()">...</button>
        </span>
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-success">{{import}}</button> 
    <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>
<script>
	function choose_file() {
        $('#kategori_jasa').click();
    }

    function select_file() {
        $('#file').val($('#kategori_jasa').val().split('\\').pop());
    }
</script>