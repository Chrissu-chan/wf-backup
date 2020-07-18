<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->config->item('application_name') ?></title>
    <link rel="icon" href="<?= $this->config->item('logo') ?>" type="image/x-icon"/>
    <link href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/css/theme/default.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/style.css') ?>" rel="stylesheet"/>
</head>

<body class="pace-top bg-white">
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <div id="page-container" class="fade">
        <div class="login login-with-news-feed">
            <div class="news-feed">
                <div class="news-image">
                    <img src="<?= base_url($this->config->item('login_background')) ?>" class="img-responsive" data-id="login-cover-image" alt="" />
                </div>
            </div>
            <div class="right-content">
                <div class="login-header">
                    <div class="brand">
                        <b><?= $this->config->item('application_name') ?></b>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <div class="login-content">
                    <?php $this->template->view('layouts/partials/message') ?>
                    <?php $this->template->view('layouts/partials/validation') ?>
                    <form action="<?= $this->route->name('login/authenticate') ?>" method="POST" class="margin-bottom-0">
                        <div class="form-group has-feedback">
                            <?= $this->form->select('cabang',
                                lists($this->cabang_m->get(), 'id', 'nama'),
                                null,
                                'class="form-control input-lg"')
                            ?>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" name="username" class="form-control input-lg" placeholder="Username" required />
                            <span class="form-control-feedback"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="password" class="form-control input-lg" placeholder="Password" required />
                            <span class="form-control-feedback"><i class="fa fa-lock"></i></span>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg">{{login}}</button>
                        </div>

                        <hr />
                        <p class="text-center">
                            &copy; {{uone_haltec_2018}}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('public/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery-ui/ui/minified/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jquery-cookie/jquery.cookie.js') ?>"></script>
    <script src="<?= base_url('public/js/apps.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
</body>
</html>