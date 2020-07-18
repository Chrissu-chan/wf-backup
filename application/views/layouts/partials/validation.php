<?php if ($CI->session->flashdata('validation_message')) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-ban"></i> Form tidak terisi dengan benar!</h4>
		<?= $CI->session->flashdata('validation_message') ?>
	</div>
<?php } ?>