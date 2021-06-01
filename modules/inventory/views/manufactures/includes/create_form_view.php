<div class="modal-dialog  modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><?= (isset($manufacture) ? 'Update Manufacture' : 'Create Manufacture'); ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" size="">
			<?php
			echo form_open('', array('id' => 'id_create_view_form'));
			?>
			<input type="hidden" name="id" id="id" value="<?= (isset($manufacture) && $manufacture->id) ? $manufacture->id : '' ?>">

			<div class="col-md-12">
				<div class="form-group" app-field-wrapper="name">
					<label for="name" class="control-label"><?php echo _l('Name'); ?></label>
					<input type="text" id="name" name="name" class="form-control"
					       value="<?= (isset($manufacture) ? $manufacture->name : ''); ?>">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group" app-field-wrapper="description">
					<label for="description" class="control-label"><?php echo _l('Description'); ?></label>
					<input type="text" id="description" name="description" class="form-control"
					       value="<?= (isset($manufacture) ? $manufacture->description : ''); ?>">
				</div>
			</div>
			<div class="form-check">
				<div class="form-group" app-field-wrapper="status">
					<label for="status" class="control-label"><?php echo _l('Status'); ?></label>
					<input type="checkbox" id="status" name="status" class="form-control" <?= (isset($manufacture) && $manufacture->status == 'active' ? 'checked' : ''); ?>
					       value="active">
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">
				<?php echo _l('Cancel') ?>
			</button>
			<button type="button" class="btn btn-primary" id="btn_create_view"><?php echo _l('Create') ?></button>
		</div>
		<?php
		echo form_close();
		?>
	</div>
</div>