<?php if ($CI->session->flashdata('import_success_message')) { ?>
	<div class="alert alert-success alert-dismissible" style="max-height:200px; overflow:auto">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-check"></i> {{import_success}} : <?= $CI->session->flashdata('import_success_message') ?></h4>		
	</div>
<?php } ?>
<?php if ($CI->session->flashdata('import_error_message')) { ?>
	<div class="alert alert-danger alert-dismissible" style="max-height:200px; overflow:auto">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-check"></i> {{import_failed}} : <?= count($CI->session->flashdata('import_error_message')) ?></h4>		
		<?php foreach ($CI->session->flashdata('import_error_message') as $error_message) { ?>
			<p><?= $error_message ?></p>
		<?php } ?>		
	</div>
<?php } ?>