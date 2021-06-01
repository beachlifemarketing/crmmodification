<div class="modal-dialog  modal-lg" role="document">
	<div class="modal-content">
		<?php
		echo form_open('', array('id' => 'id_create_view_form'));
		?>
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><?= isset($product) ? 'Update Product' : 'Create Product'; ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" size="">
			<input type="hidden" name="product_id" id="product_id" value="<?= (isset($product) && $product->product_id) ? $product->product_id : '' ?>">
			<div class="col-md-12">
				<?php
				if (!isset($product) || !isset($product->product_id)) {
					?>
					<h6>Affter create product success, you can add image to product <span class="badge badge-info">INFO</span></h6>
					<?php
				}else{
					if(isset($tab) && $tab === 'product_images'){
				?>
						<h6>Create Product Success, you can add image to product <span class="badge badge-info">INFO</span></h6>
				<?php
					}
				}
				?>
				<p></p>
				<div class="horizontal-scrollable-tabs">
					<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
					<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
					<div class="horizontal-tabs">
						<ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
							<li role="presentation" class="<?= (isset($tab) && $tab === 'product_info') ? 'active' : '' ?>">
								<a href="#product_info" aria-controls="product_info" role="tab" data-toggle="tab">
									Informations
								</a>
							</li>
							<li role="presentation" class="<?= (isset($tab) && $tab === 'product_descriptions') ? 'active' : '' ?>">
								<a href="#product_descriptions" aria-controls="product_descriptions" role="tab" data-toggle="tab">
									Descriptions
								</a>
							</li>
							<?php
							if (isset($product) && $product->product_id) {
								?>
								<li role="presentation" class="<?= (isset($tab) && $tab === 'product_images') ? 'active' : '' ?>">
									<a href="#product_images" aria-controls="product_images" role="tab" data-toggle="tab">
										Images
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="tab-content mtop15">
					<div role="tabpanel" class="tab-pane <?= (isset($tab) && $tab === 'product_info') ? 'active' : '' ?>" id="product_info">
						<div class="row">
							<!--<div class="col-md-12">
								<div class="input-group col-md-12">
									<div class="input-group-prepend">
										<label class="input-group-text" for="client_id">Customer</label>
									</div>
									<select class="form-control selectpicker col-md-12" id="client_id" name="client_id" data-width="100%" data-live-search="true">
										<option value="">Choose Manufacture...</option>
										<?php
							/*										if (isset($clients)) {

																		$selected = '';
																		foreach ($clients as $client) {
																			if (isset($product) && $product->client_id == $client['userid']) {
																				$selected = 'selected';
																			}
																			*/ ?>
												<option <? /*= $selected */ ?> value="<? /*= $client['userid'] */ ?>"><? /*= $client['company'] */ ?></option>
												<?php
							/*											}
																	}
																	*/ ?>
									</select>
								</div>
							</div>-->
							<div class="clearfix"></div>
							<div class="col-md-12">
								<div class="input-group col-md-12">
									<div class="input-group-prepend">
										<label class="input-group-text" for="manufacturer_id">Manufactures</label>
									</div>
									<select class="form-control col-md-12 selectpicker" id="manufacturer_id" name="manufacturer_id" data-width="100%" data-live-search="true">
										<option>Choose Manufacture...</option>
										<?php
										if (isset($manufactures)) {

											$selected = '';
											foreach ($manufactures as $item) {
												if (isset($product) && $product->manufacturer_id == $item['id']) {
													$selected = 'selected';
												}
												?>
												<option <?= $selected ?> value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group" app-field-wrapper="product_code">
									<label for="product_code" class="control-label"><?php echo _l('Code'); ?></label>
									<input type="text" id="product_code" name="product_code" class="form-control"
									       value="<?= (isset($product) ? $product->product_code : ''); ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group" app-field-wrapper="product_name">
									<label for="product_name" class="control-label"><?php echo _l('Name'); ?></label>
									<input type="text" id="product_name" name="product_name" class="form-control"
									       value="<?= (isset($product) ? $product->product_name : ''); ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group" app-field-wrapper="quantity">
									<label for="quantity" class="control-label"><?php echo _l('Quantity'); ?></label>
									<input type="number" id="quantity" name="quantity" class="form-control"
									       value="<?= (isset($product) ? $product->quantity : ''); ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-check">
									<div class="form-group" app-field-wrapper="product_status">
										<label for="product_status" class="control-label"><?php echo _l('Status'); ?></label>
										<input type="checkbox" id="product_status" name="product_status" class="form-control" <?= (isset($product) && $product->product_status == 'active' ? 'checked' : ''); ?>
										       value="active">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane <?= (isset($tab) && $tab === 'product_descriptions') ? 'active' : '' ?>" id="product_descriptions">
						<div class="col-md-12">
							<?php echo render_textarea('product_description', _l('Description'), (isset($product) ? $product->product_description : ''), array(), array(), '', 'tinymce-product'); ?>
						</div>
					</div>
					<?php
					if (isset($product) && $product->product_id) {
						?>
						<div role="tabpanel" class="tab-pane <?= (isset($tab) && $tab === 'product_images') ? 'active' : '' ?>" id="product_images">
							<div class="col-md-12">
								<div class="form-group" app-field-wrapper="images">
									<label for="images" class="control-label"><?php echo _l('Images'); ?></label>
									<div class="col-md-12" id="row_file_products">
										<?php $this->load->view('products/includes/product_files'); ?>

									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>

				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">
				<?php echo _l('Cancel') ?>
			</button>
			<button type="button" class="btn btn-primary" id="btn_create_view"><?= (isset($product) && $product->product_id) ? 'Update' : 'Create' ?></button>
		</div>
		<?php
		echo form_close();
		?>
	</div>
	<script>
        $(function () {
            init_datepicker();
            init_selectpicker();
            init_editor('#product_description');
        });
	</script>
</div>
