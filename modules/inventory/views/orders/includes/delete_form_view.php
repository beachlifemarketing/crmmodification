<div class="modal-dialog  modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><?php echo _l('Delete') ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<?php
		$id = (isset($order) && $order->order_id) ? $order->order_id : '';
		?>
		<div class="modal-body" size="">
			<?php
			echo form_open('', array('id' => 'id_delete_view_form'));
			?>
			<input type="hidden" name="id" id="id" value="<?= $id ?>">

			<div class="col-md-12 center-block">
				<h4><?= _l('Are you sure delete #') . $id . '?' ?></h4>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">
				<?php echo _l('Cancel') ?>
			</button>
			<button type="button" class="btn btn-danger" id="btn_delete_view"><?php echo _l('Delete') ?></button>
		</div>
		<?php
		echo form_close();
		?>
	</div>
</div>