<div class="modal-header">
	<button type="button" class="close" onclick="close_modal_manually('._product_file'); return false;">
		<span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $file->subject; ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-6 border-right project_file_area">
			<?php
			if ($file->staffid == get_staff_user_id() || has_permission('products', '', 'create')) {
				?>
				<?php echo render_input('file_subject', 'product_discussion_subject', $file->subject, 'text', ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
				<?php echo render_textarea('file_description', 'product_discussion_description', $file->description, ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
				<hr/>
				<?php
			} ?>
			<?php
			$path = FCPATH . 'uploads/products' . '/' . $file->product_id . '/' . $file->file_name;
			if (is_image($path)) {
				?>
				<img src="<?php echo base_url('uploads/products/' . $file->product_id . '/' . $file->file_name); ?>"
				     class="img img-responsive">
				<?php
			} elseif (!empty($file->external) && !empty($file->thumbnail_link) && $file->external == 'dropbox') {
				?>
				<img src="<?php echo optimize_dropbox_thumbnail($file->thumbnail_link); ?>"
				     class="img img-responsive">
				<?php
			} elseif (strpos($file->filetype, 'pdf') !== false && empty($file->external)) {
				?>
				<iframe src="<?php echo base_url('uploads/products/' . $file->product_id . '/' . $file->file_name); ?>"
				        height="100%" width="100%" frameborder="0"></iframe>
				<?php
			} elseif (is_html5_video($path)) {
				?>
				<video width="100%" height="100%"
				       src="<?php echo site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $file->filetype); ?>"
				       controls>
					Your browser does not support the video tag.
				</video>
				<?php
			} elseif (is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
				echo $previewMarkdown;
			} else {
				if (empty($file->external)) {
					echo '<a href="' . site_url('uploads/products/' . $file->product_id . '/' . $file->file_name) . '" download>' . $file->file_name . '</a>';
				} else {
					echo '<a href="' . $file->external_link . '" target="_blank">' . $file->file_name . '</a>';
				}
				echo '<p class="text-muted">' . _l('no_preview_available_for_file') . '</p>';
			} ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="modal-footer">
	<div style="float: left">
		<?php if (isset($file_next)) { ?>
			<button type="button" class="modal-title" style="margin-right: 15px;"
			        onclick="view_product_file('<?= $file_next->id ?>', '<?= $file_next->product_id ?>'); return false;">
				<i class="fa fa-arrow-left"></i></button>
		<?php } ?>
		<!--bootstrap card with 3 horizontal images-->

		<?php if (isset($file_next) && is_image(FCPATH . 'uploads/products' . '/' . $file_next->product_id . '/' . $file_next->file_name) || (!empty($file_next->external) && !empty($file_next->thumbnail_link))) {
			echo '<img onclick="view_product_file(\'' . $file_next->id . '\', \'' . $file_next->product_id . '\'); return false;" class="modal-title img-width-50 product-file-image" src="' . product_file_url(get_object_vars($file_next), true) . '" width="80">';
		} ?>

		<?php if (isset($file) && is_image(FCPATH . 'uploads/products' . '/' . $file->product_id . '/' . $file->file_name) || (!empty($file->external) && !empty($file->thumbnail_link))) {
			echo '<img class="modal-title product-file-image" src="' . product_file_url(get_object_vars($file), true) . '" width="50" height="50" style="border:1px solid black">';
		} ?>

		<?php if (isset($file_previous) && is_image(FCPATH . 'uploads/products' . '/' . $file_previous->product_id . '/' . $file_previous->file_name) || (!empty($file_previous->external) && !empty($file_previous->thumbnail_link))) {
			echo '<img onclick="view_product_file(\'' . $file_previous->id . '\', \'' . $file_previous->product_id . '\'); return false;" class="modal-title img-width-50 product-file-image" src="' . product_file_url(get_object_vars($file_previous), true) . '" width="80">';
		} ?>

		<?php if (isset($file_previous)) { ?>
			<button type="button" class="modal-title" style="margin-right: 15px;"
			        onclick="view_product_file('<?= $file_previous->id ?>', '<?= $file_previous->product_id ?>'); return false;">
				<i
						class="fa fa-arrow-right"></i></button>
		<?php } ?>
	</div>
	<button type="button" class="btn btn-default"
	        onclick="close_modal_manually('._product_file'); return false;"><?php echo _l('close'); ?></button>
</div>