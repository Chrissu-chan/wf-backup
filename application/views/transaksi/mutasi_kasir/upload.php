<?= $this->form->open_multipart($this->route->name('transaksi.mutasi_kasir.upload_store'), 'id="frm-upload"') ?>
<?= $this->form->hidden('id', $id) ?>
<div class="form-group">
    <div class="input-group input-group-sm">
        <?= $this->form->text('file', null, 'id="file" class="form-control"  onclick="choose_file()" readonly') ?>
        <?= $this->form->upload('file_path', null, 'id="file_path" onchange="select_file()" style="display: none;"') ?>
        <span class="input-group-btn">
            <button type="button" class="btn btn-default" onclick="choose_file()">...</button>
        </span>
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-success">{{upload}}</button> 
    <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>
<script>
    function choose_file() {
        $('#file_path').click();
    }

    function select_file() {
        $('#file').val($('#file_path').val().split('\\').pop());
    }
</script>