<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="product-files-upload" style="overflow-x:auto;">
	<input type="file" name="file" id="file_estimate" multiple/>
	<button class="btn btn-info only-save customer-form-submiter" type="button" onclick="saveFile();return false;">
		Save File
	</button>
	<div class="clearfix"></div>
	<table class="table table-bordered table-striped mb-0 table-product-files" data-order-col="7"
	       data-order-type="desc">
		<thead>
		<tr>
			<th style="min-width: 100px"><?php echo _l('File name'); ?></th>
			<th style="min-width: 100px"><?php echo _l('File type'); ?></th>
			<th style="min-width: 100px"><?php echo _l('Upload by'); ?></th>
			<th style="min-width: 100px"><?php echo _l('Date add'); ?></th>
			<th style="min-width: 100px"><?php echo _l('options'); ?></th>
		</tr>
		</thead>
		<tbody id="images_list">
		<?php $this->load->view('products/includes/product_file_data'); ?>
		</tbody>
	</table>
	<div id="product_file_data"></div>
	<div class="clearfix"></div>
</div>

