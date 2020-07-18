<?php
	require APPPATH.'/config/config.php';
	$base_url = $config['base_url'];
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>404 Page Not Found</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="<?= $base_url ?>/public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/public/css/theme/default.css" rel="stylesheet"/>
    <link href="<?= $base_url ?>/public/css/style.css" rel="stylesheet"/>
</head>
<body class="pace-top">
	<div id="page-container">
        <div class="error">
            <div class="error-code m-b-10">404 <i class="fa fa-warning"></i></div>
            <div class="error-content">
                <div class="error-message">We couldn't find it...</div>
                <div class="error-desc m-b-20">
                    The page you're looking for doesn't exist. <br />
                    Perhaps, there pages will help find what you're looking for.
                </div>
                <div>
                    <a href="<?= $base_url ?>" class="btn btn-success btn-lg">Go Back to Home Page</a>
                </div>
            </div>
        </div>
	</div>
</body>
</html>
