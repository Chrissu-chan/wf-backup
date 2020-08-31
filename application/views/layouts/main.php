<?php
$CI->load->model('cabang_m');
$CI->load->model('shift_aktif_m');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <?php if (isset($judul)) { ?>
        <title><?= $judul; ?></title>
    <?php } else if (!isset($title)) { ?>
        <title><?= $this->config->item('application_name'); ?></title>
    <?php } else { ?>
        <title><?= $title; ?></title>
    <?php } ?>
    <link rel="icon" href="<?= base_url('public/images/config/logo.png'); ?>" type="image/x-icon" />
    <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />-->
    <link href="<?= base_url('public/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/animate.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/style.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/style-responsive.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/invoice-print.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/theme/default.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/css/admin.style.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/sweetalert/dist/sweetalert.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/sweetalert/themes/google/google.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/DataTables/media/css/dataTables.bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/treetable/css/jquery.treetable.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/treetable/css/jquery.treetable.theme.default.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/bootstrap-datepicker/css/datepicker.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/bootstrap-datepicker/css/datepicker3.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/bootstrap-datetimepicker/css/datetimepicker.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('public/plugins/jquery.growl/css/jquery.growl.css') ?>" rel="stylesheet" />
    <?php $CI->template->render('css') ?>
    <script src="<?= base_url('public/plugins/pace/pace.min.js') ?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <div id="header" class="header navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="<?= base_url() ?>" class="navbar-brand"><i class="fa fa-desktop"></i> <?= $CI->config->item('application_name') ?></a>
                    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown navbar-user">
                        <?php if ($shift_aktif = $CI->shift_aktif_m->view('shift_aktif')->scope('cabang')->scope('aktif')->first()) { ?>
                            <a href="<?= $this->route->name('transaksi.shift_aktif.close') ?>"><i class="fa fa-clock-o"></i> {{shift_aktif}} : <?= $shift_aktif->shift_waktu ?></a>
                        <?php } else { ?>
                            <a href="<?= $this->route->name('transaksi.shift_aktif.open') ?>"><i class="fa fa-clock-o"></i> {{open_shift}}</a>
                        <?php } ?>
                    </li>
                    <li class="dropdown navbar-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-home fa-fw"></i><span class="hidden-xs"> <?= $CI->session->userdata('cabang')->nama ?></span>
                        </a>
                        <ul class="dropdown-menu animated fadeInLeft">
                            <li class="arrow"></li>
                            <?php
                            foreach ($CI->cabang_m->scope('auth')->get() as $cabang) {
                            ?>
                                <li><a href="<?= $this->route->name('aktif_cabang.set', array('id' => $cabang->id)) ?>"><?= $cabang->nama ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown navbar-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= ($CI->auth->photo) ? base_url($this->config->item('photo_upload_path') . '/' . $CI->auth->photo) : base_url('public/images/default-user-photo.jpg') ?>" alt="">
                            <span class="hidden-xs"><?= $CI->auth->name ?></span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu animated fadeInLeft">
                            <li class="arrow"></li>
                            <li><a href="<?= $CI->route->name('user_setting') ?>">{{setting}}</a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $CI->route->name('logout') ?>">{{log_out}}</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div id="sidebar" class="sidebar">
            <div data-scrollbar="true" data-height="100%">
                <?php $CI->menu->load($CI->config->item('application_id'), 'layouts/sidebar/main') ?>
            </div>
        </div>
        <div class="content">
            <?php $CI->template->render('content') ?>
        </div>
        <?php if ($CI->template->has_section('filter')) { ?>
            <div class="theme-panel theme-panel-lg">
                <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-filter"></i></a>
                <div class="theme-panel-content">
                    <h4 class="m-t-0">{{filter}}</h4>
                    <div class="divider"></div>
                    <?php $CI->template->render('filter') ?>
                    <div class="divider"></div>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <a href="javascript:void(0);" class="btn btn-primary btn-block" onclick="filter()">{{filter}}</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="<?= base_url('public/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/js/moment.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery-ui/ui/minified/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!--[if lt IE 9]-->
    <script src="<?= base_url('public/crossbrowserjs/html5shiv.js') ?>"></script>
    <script src="<?= base_url('public/crossbrowserjs/respond.min.js') ?>"></script>
    <script src="<?= base_url('public/crossbrowserjs/excanvas.min.js') ?>"></script>
    <!--[endif]-->
    <script src="<?= base_url('public/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/sweetalert/dist/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootbox/bootbox.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/DataTables/media/js/jquery.dataTables.js') ?>"></script>
    <script src="<?= base_url('public/plugins/DataTables/media/js/dataTables.bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/DataTables/extensions/Select/js/dataTables.select.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/DataTables/extensions/TreeGrid/dataTables.treeGrid.js') ?>"></script>
    <script src="<?= base_url('public/plugins/treetable/jquery.treetable.js') ?>"></script>
    <script src="<?= base_url('public/plugins/select2/dist/js/select2.full.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery.growl/js/jquery.growl.js') ?>"></script>
    <script src="<?= base_url('public/plugins/chart-js/Chart2.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery-number/jquery.number.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/input-mask/jquery.inputmask.js') ?>"></script>
    <?php $CI->template->render('script') ?>
    <script src="<?= base_url('public/js/apps.min.js') ?>"></script>
    <script src="<?= base_url('public/js/build.js') ?>"></script>
    <script src="<?= base_url('public/js/localization.js') ?>"></script>
    <script src="<?= base_url('public/js/locale.js') ?>"></script>
    <?php $CI->template->render('page_script') ?>

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
</body>

</html>