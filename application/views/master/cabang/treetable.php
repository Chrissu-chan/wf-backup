<table id="table" width="100%" class="table table-bordered table-condensed ">
    <thead>
        <tr>
            <th>{{nama}}</th>
            <th width="150">{{npwp}}</th>
            <th width="150">{{telepon}}</th>
            <th width="350">{{alamat}}</th>
            <td width="1"></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model as $row) { ?>
            <tr>
                <td><?= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $row->tree_level) ?><?= $row->nama ?></td>
                <td><?= $row->npwp ?></td>
                <td><?= $row->telepon ?></td>
                <td><?= $row->alamat ?></td>
                <td class="text-right nowrap">
                    <?= $this->action->button('view', 'class="btn btn-info btn-sm" onclick="view(\''.$row->id.'\')"') ?>
                    <?= $this->action->button('edit', 'class="btn btn-warning btn-sm" onclick="edit(\''.$row->id.'\')"') ?>
                    <?= $this->action->button('delete', 'class="btn btn-danger btn-sm" onclick="remove(\''.$row->id.'\')"') ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>