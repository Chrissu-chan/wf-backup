<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $this->config->item('application_name') ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <link rel="icon" href="<?= $this->config->item('logo') ?>" type="image/x-icon"/>
    <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />-->
    <link href="<?= base_url('public/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/animate.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/style.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/style-responsive.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/invoice-print.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/theme/default.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/admin.style.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/sweetalert/dist/sweetalert.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/sweetalert/themes/google/google.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/DataTables/media/css/dataTables.bootstrap.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/treetable/css/jquery.treetable.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/treetable/css/jquery.treetable.theme.default.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/bootstrap-datepicker/css/datepicker.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/bootstrap-datepicker/css/datepicker3.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/bootstrap-datetimepicker/css/datetimepicker.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugins/jquery.growl/css/jquery.growl.css') ?>" rel="stylesheet"/>
    <?php $this->template->render('css') ?>
    <script src="<?= base_url('public/plugins/pace/pace.min.js') ?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="background: none; overflow-x: hidden">
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <?php $this->template->view('layouts/partials/message') ?>
    <?php $this->template->render('content') ?>
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
    <script src="<?= base_url('public/plugins/action-column/action-column.js') ?>"></script>
    <?php $this->template->render('script') ?>
    <script src="<?= base_url('public/js/apps.min.js') ?>"></script>
    <script src="<?= base_url('public/js/build.js') ?>"></script>
    <script src="<?= base_url('public/js/localization.js') ?>"></script>
    <script src="<?= base_url('public/js/indonesia.js') ?>"></script>
    <?php $this->template->render('page_script') ?>

    <script>
        $(document).ready(function () {
            App.init();
            // TableManageDefault.init();
        });
    </script>
</body>
</html>