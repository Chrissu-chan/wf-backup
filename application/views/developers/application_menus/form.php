<div id="frm-message"></div>
<?= $this->form->hidden('parent_id', $parent_id, 'id="parent_id"') ?>
<div class="form-group">
    <label>{{menu}}</label>
    <?= $this->form->text('menu', null, 'id="menu" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{description}}</label>
    <?= $this->form->text('description', null, 'id="description" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{action}}</label>
    <?= $this->form->select(
        'module_feature_action_id',
        lists($this->module_feature_actions_m->view('actions')->get(), 'id', function($row) {
            return $row->module.' > '.$row->feature.' > '.$row->action;
        }, $this->localization->lang('without_action'), 0),
        null,
        'id="module_feature_action_id" class="form-control" data-input-type="select2"'
    ) ?>
</div>
<div class="form-group">
    <label>{{url}}</label>
    <?= $this->form->text('url', null, 'id="url" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{icon}}</label>
    <?= $this->form->text('icon', null, 'id="icon" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{attributes}}</label>
    <?= $this->form->text('attributes', null, 'id="attributes" class="form-control"') ?>
</div>