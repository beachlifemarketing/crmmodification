<div class="modal-dialog  modal-lg" role="document">
	<div class="modal-content">
		<?php
		echo form_open('', array('order_id' => 'id_create_view_form'));
		?>
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><?= isset($order) ? 'Update Order' : 'Create Order'; ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" size="">
			<input type="hidden" name="order_id" id="order_id" value="<?= (isset($order) && $order->order_id) ? $order->order_id : '' ?>">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="input-group col-md-12">
						<div class="input-group-prepend">
							<label class="input-group-text" for="order_client_id">Customer</label>
						</div>
						<select <?= (isset($order) ? 'disabled' : ''); ?> class="form-control selectpicker col-md-12" id="order_client_id" name="order_client_id" data-width="100%" data-live-search="true">
							<option value="">Choose Customer...</option>
							<?php
							if (isset($clients)) {
								$selected = '';
								foreach ($clients as $client) {
									if (isset($order) && $order->order_client_id == $client['userid']) {
										$selected = 'selected';
									}
									?>
									<option <?= $selected ?> value="<?= $client['userid'] ?>"><?= $client['company'] ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-12">
					<div class="input-group col-md-12">
						<div class="input-group-prepend">
							<label class="input-group-text" for="order_product_id">Products</label>
						</div>
						<select <?= (isset($order) ? 'disabled' : ''); ?> class="form-control col-md-12 selectpicker" id="order_product_id" name="order_product_id" data-width="100%" data-live-search="true" multiple>
							<option>Choose Product...</option>
							<?php
							if (isset($products)) {

								$selected = '';
								foreach ($products as $item) {
									if (isset($order) && $order->order_product_id == $item['product_id']) {
										$selected = 'selected';
									}
									?>
									<option <?= $selected ?> value="<?= $item['product_id'] ?>">Name: <?= $item['product_name'] ?> - Quantity: <?= $item['quantity'] ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group" app-field-wrapper="order_number">
						<label for="order_number" class="control-label"><?php echo _l('Order number'); ?></label>
						<input type="text" <?= (isset($order) ? 'readonly' : ''); ?> id="order_number" name="order_number" class="form-control"
						       value="<?= (isset($order) ? $order->order_number : ''); ?>">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group" app-field-wrapper="order_quantity">
						<label for="order_quantity" class="control-label"><?php echo _l('Quantity'); ?></label>
						<input type="number" id="quantity" <?= (isset($order) ? 'readonly' : ''); ?> name="order_quantity" class="form-control"
						       value="<?= (isset($order) ? $order->order_quantity : ''); ?>">
					</div>
				</div>
				<div class="col-md-12">
					<?php $value = (isset($order) ? _d($order->due_order_date) : _d(date('Y-m-d'))); ?>
					<?php echo render_date_input('due_order_date', 'Due date', $value); ?>
				</div>
				<div class="col-md-12">
					<div class="input-group col-md-12">
						<div class="input-group-prepend">
							<label class="input-group-text" for="order_status">Status</label>
						</div>
						<select class="form-control col-md-12" <?= isset($order) && $order->order_status !== 'inprocessing' ? 'disabled' : '' ?> id="order_status" name="order_status" data-width="100%">
							<?php
							if (isset($status)) {
								foreach ($status as $key => $value) {
									?>
									<option <?= isset($order) && $order->order_status === $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<?php echo render_textarea('order_note', _l('Note'), (isset($order) ? $order->order_note : ''), array(), array(), '', 'tinymce-order'); ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">
				<?php echo _l('Cancel') ?>
			</button>
			<button type="button" class="btn btn-primary" id="btn_create_view"><?= (isset($order) && $order->order_id) ? 'Update' : 'Create' ?></button>
		</div>
		<?php
		echo form_close();
		?>
	</div>
	<script>
        $(function () {
            init_datepicker();
            init_selectpicker();
            init_editor('#order_note');
        });
	</script>
</div>
