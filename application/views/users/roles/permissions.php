<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{Permissions}}
        <small><i class="fa fa-angle-right"></i> <?= $role->role ?></small>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, $this->route->name('users.roles.permissions_save', array('id' => $role->id))) ?>
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th width="200px">{{Permission}}</th>
                        <th class="text-center">{{action}}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissions as $application_id => $application) { ?>
                        <tr style="background: #f0f3f5">
                            <td colspan="2">
                                <?= $this->form->checkbox('applications[]', 1, false, 'data-application_id="'.$application_id.'" onclick="check_application(\''.$application_id.'\')"') ?>
                                <b><?= $application['application'] ?></b>
                            </td>
                        </tr>
                        <?php foreach ($application['modules'] as $module_id => $module) { ?>
                            <tr>
                                <td colspan="2" style="padding-left: 30px;">
                                    <?= $this->form->checkbox('modules[]', 1, false, 'data-application_id="'.$application_id.'" data-module_id="'.$module_id.'" onclick="check_module(\''.$application_id.'\', \''.$module_id.'\')"') ?>
                                    <b><?= $module['module'] ?></b>
                                </td>
                            </tr>
                            <?php foreach ($module['features'] as $feature_id => $feature) { ?>
                                <tr>
                                    <td style="padding-left: 60px;">
                                        <?= $this->form->checkbox('features[]', 1, false, 'data-application_id="'.$application_id.'" data-module_id="'.$module_id.'" data-feature_id="'.$feature_id.'" onclick="check_feature(\''.$application_id.'\', \''.$module_id.'\', \''.$feature_id.'\')"') ?>
                                        <?= $feature['feature'] ?>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <?php foreach ($feature['actions'] as $action) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?= $this->form->checkbox('permissions['.$application['application_id'].']['.$action['module_feature_action_id'].']', 1, false, 'data-application_id="'.$application_id.'" data-module_id="'.$module_id.'" data-feature_id="'.$feature_id.'" onclick="check_parent()"') ?>
                                                        <?= $action['label'] ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            <div class="form-group">
                <button type="submit" class="btn btn-success">{{save}}</button>
                <a href="<?= $this->route->name('users.roles') ?>" class="btn btn-default">{{cancel}}</a>
            </div>
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        check_parent();
    });

    function check_parent() {
        $.each($('[name="applications[]"]'), function(key, elem) {
            var application_id = $(elem).data('application_id');
            if ($('[name*="permissions"][data-application_id="'+application_id+'"]:checked').length > 0) {
                $(elem).prop('checked', true);
            } else {
                $(elem).prop('checked', false);
            }
        });

        $.each($('[name="modules[]"]'), function(key, elem) {
            var application_id = $(elem).data('application_id');
            var module_id = $(elem).data('module_id');
            if ($('[name*="permissions"][data-application_id="'+application_id+'"][data-module_id="'+module_id+'"]:checked').length > 0) {
                $(elem).prop('checked', true);
            } else {
                $(elem).prop('checked', false);
            }
        });

        $.each($('[name="features[]"]'), function(key, elem) {
            var application_id = $(elem).data('application_id');
            var module_id = $(elem).data('module_id');
            var feature_id = $(elem).data('feature_id');
            if ($('[name*="permissions"][data-application_id="'+application_id+'"][data-module_id="'+module_id+'"][data-feature_id="'+feature_id+'"]:checked').length > 0) {
                $(elem).prop('checked', true);
            } else {
                $(elem).prop('checked', false);
            }
        });
    }

    function check_application(application_id) {
        $('[data-application_id="'+application_id+'"]').prop('checked', $('[name="applications[]"][data-application_id="'+application_id+'"]').prop('checked'));
    }

    function check_module(application_id, module_id) {
        $('[data-application_id="'+application_id+'"][data-module_id="'+module_id+'"]').prop('checked', $('[name="modules[]"][data-application_id="'+application_id+'"][data-module_id="'+module_id+'"]').prop('checked'));
    }

    function check_feature(application_id, module_id, feature_id) {
        $('[data-application_id="'+application_id+'"][data-module_id="'+module_id+'"][data-feature_id="'+feature_id+'"]').prop('checked', $('[name="features[]"][data-application_id="'+application_id+'"][data-module_id="'+module_id+'"][data-feature_id="'+feature_id+'"]').prop('checked'));
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>