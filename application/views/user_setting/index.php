<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{user_setting}}
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <?php $this->template->view('layouts/partials/validation') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="content-title m-t-0"><i class="fa fa-user"></i> {{profile}}</h3>
                </div>
                <div class="col-md-8">
                    <?= $this->form->open_multipart($this->route->name('user_setting.save_profile'), null, null, false) ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="<?= ($this->auth->photo) ? base_url($this->config->item('photo_upload_path').'/'.$this->auth->photo) : base_url('public/images/default-user-photo.jpg') ?>" id="img-photo" class="img-responsive" data-default-src="<?= ($this->auth->photo) ? base_url($this->config->item('photo_upload_path').'/'.$this->auth->photo) : base_url('public/images/default-user-photo.jpg') ?>" width="100%">
                                </div>
                                <div class="col-md-8">
                                    <?= $this->form->upload('photo', null, 'id="photo" accept="image/*" style="display: none;"') ?>
                                    <button type="button" class="btn btn-default" onclick="browse_file()">{{pilih_file}}</button>
                                    <span>{{tidak_ada_file_yang_dipilih}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{name}}</label>
                            <?= $this->form->text('name', $this->auth->name, 'class="form-control"') ?>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success">{{save_profile}}</button>
                        </div>
                    <?= $this->form->close() ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <h3 class="content-title m-t-0"><i class="fa fa-lock"></i> {{security}}</h3>
                </div>
                <div class="col-md-8">
                    <?= $this->form->open($this->route->name('user_setting.change_password'), null, null, false) ?>
                        <div class="form-group">
                            <label>{{old_password}}</label>
                            <?= $this->form->password('old_password', null, 'class="form-control"') ?>
                        </div>
                        <div class="form-group">
                            <label>{{new_password}}</label>
                            <?= $this->form->password('new_password', null, 'class="form-control"') ?>
                        </div>
                        <div class="form-group">
                            <label>{{retype_new_password}}</label>
                            <?= $this->form->password('retype_new_password', null, 'class="form-control"') ?>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success">{{change_password}}</button>
                        </div>
                    <?= $this->form->close() ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        $('#photo').change(function() {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-photo').prop('src', e.target.result);
            }
            reader.readAsDataURL($('#photo')[0].files[0]);
        });
    });

    function browse_file() {
        $('#photo').click();
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>