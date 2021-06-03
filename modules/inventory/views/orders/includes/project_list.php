<div class="input-group col-md-12" id="div_order_project_id">
	<div class="input-group-prepend">
		<label class="input-group-text" for="order_project_id">Projects</label>
	</div>
	<select <?= (isset($order) ? 'disabled' : ''); ?> class="form-control selectpicker col-md-12" id="order_project_id" name="order_project_id" data-width="100%" data-live-search="true">
		<option value="">Choose Project...</option>
		<?php
		if (isset($projects)) {
			$selected = '';
			foreach ($projects as $project) {
				if (isset($order) && $order->order_project_id == $project['id']) {
					$selected = 'selected';
				}
				?>
				<option <?= $selected ?> value="<?= $project['id'] ?>"><?= $project['name'] ?></option>
				<?php
			}
		}
		?>
	</select>
</div>

<script>
    $(function () {
        init_selectpicker();
    });
</script>