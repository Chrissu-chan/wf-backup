<?= $this->form->open(null, 'id="frm-create"') ?>
<div class="form-group">
    <label>{{pelanggan}}</label>
    <?= $this->form->select('id_pelanggan', lists($this->pelanggan_m->get(), 'id', 'nama', $this->localization->lang('Pelanggan Baru'), 0), null, 'id="id_pelanggan" class="form-control" data-input-type="select2" style="width:100%" onchange="find_pelanggan()"') ?>
</div>
<?= $this->template->view('member/member/form') ?>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="store()">{{store}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>

<script>
    $(function() {
        $('#frm-create').buildForm();
    });
</script>