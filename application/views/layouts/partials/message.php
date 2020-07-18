<?php if ($CI->session->flashdata('success_message')) { ?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-check"></i> <?= $CI->session->flashdata('success_message') ?></h4>
	</div>
<?php } ?>
<?php if ($CI->session->flashdata('error_message')) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-ban"></i> <?= $CI->session->flashdata('error_message') ?></h4>
	</div>
<?php } ?>