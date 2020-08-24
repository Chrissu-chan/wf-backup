<div id="frm-message"></div>
<div class="form-group">
    <label>{{kategori_jasa}}</label>
    <?= $this->form->hidden('parent_id', (isset($parent_id) ? $parent_id : ''), 'id="kategori_jasa"') ?>
    <?= $this->form->text('kategori_jasa', null, 'id="kategori_jasa" class="form-control"') ?>
    <input type="text" style="display: none;">
</div>