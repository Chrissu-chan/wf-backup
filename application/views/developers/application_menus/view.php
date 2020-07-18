<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{application_id}}</td>
        <td><?= $model->application_id ?></td>
    </tr>
    <tr>
        <td class="field">{{menu}}</td>
        <td><?= $model->menu ?></td>
    </tr>
    <tr>
        <td class="field">{{description}}</td>
        <td><?= $model->description ?></td>
    </tr>
    <tr>
        <td class="field">{{action}}</td>
        <td><?= (!is_null($model->module_feature_action_id) ? trim($model->module.'/'.$model->feature.'/'.$model->action, '/') : '') ?></td>
    </tr>
    <tr>
        <td class="field">{{url}}</td>
        <td><?= $model->url ?></td>
    </tr>
    <tr>
        <td class="field">{{icon}}</td>
        <td><label class="label label-primary"><span class="fa <?= $model->icon ?> fa-fw"></span></label> <?= $model->icon ?></td>
    </tr>
    <tr>
        <td class="field">{{attributes}}</td>
        <td><?= $model->attributes ?></td>
    </tr>
</table>