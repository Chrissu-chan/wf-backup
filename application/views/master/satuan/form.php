<div id="frm-message"></div>
<div class="form-group">
    <label>{{satuan}}</label>
    <?= $this->form->text('satuan', null, 'id="satuan" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{grup}}</label>
	<?= $this->form->text('grup', null, 'id="grup" class="form-control" style="text-transform:uppercase;"') ?>
</div>
<div class="form-group">
    <label>{{keterangan}}</label>
    <?= $this->form->textarea('keterangan', null, 'id="keterangan" class="form-control"') ?>
</div>