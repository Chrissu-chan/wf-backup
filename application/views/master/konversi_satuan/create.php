<?= $this->form->open(null, 'id="frm-create"') ?>
<?= $this->template->view('master/konversi_satuan/form') ?>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="store()">{{store}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>

<script>
    $(function() {
        $('#frm-create').buildForm();
    });
</script>
