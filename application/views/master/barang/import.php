<?= $this->form->open_multipart($this->route->name('master.barang.import_store'), 'id="frm-import"') ?>
<div class="form-group">
    <div class="input-group input-group-sm">
        <?= $this->form->text('file_path', null, 'id="file_path" class="form-control"  onclick="choose_file()" readonly') ?>
        <?= $this->form->upload('file', null, 'id="file" onchange="select_file()" style="display: none;"') ?>
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
        $('#file').click();
    }

    function select_file() {
        $('#file_path').val($('#file').val().split('\\').pop());
    }
</script>