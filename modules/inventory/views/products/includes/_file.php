<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _product_file" tabindex="-1" role="dialog" data-toggle="modal">
	<div class="modal-dialog full-screen-modal" role="document">
		<div class="modal-content" id="file_preview_content">
			<?php $this->load->view('products/includes/_file_data'); ?>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    $('body').find('._product_file').modal({show: true, backdrop: 'static', keyboard: false});
</script>
