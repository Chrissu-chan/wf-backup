<table width="100%" class="table table-profile">
    <tr>
        <td class="field">{{username}}</td>
        <td><?= $model->username ?></td>
    </tr>
    <tr>
        <td class="field">{{name}}</td>
        <td><?= $model->name ?></td>
    </tr>
    <tr>
        <td class="field">{{active}}</td>
        <td><?= $this->users_m->enum('active', $model->active) ?></td>
    </tr>
</table>