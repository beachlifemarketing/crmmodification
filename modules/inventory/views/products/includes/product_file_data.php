<?php
$files = (isset($files) ? $files : []);
?>
<?php foreach ($files as $file) {
	$path = FCPATH . 'uploads/products' . '/' . $file['product_id'] . '/' . $file['file_name'];
	?>
	<tr>
		<td data-order="<?php echo $file['file_name']; ?>">
			<a href="<?= product_file_url($file) ?>" target="_blank">
				<?php if (is_image($path) || (!empty($file['external']) && !empty($file['thumbnail_link']))) {
					echo '<img class="product-file-image img-table-loading" src="' . product_file_url($file, true) . '" data-orig="' . product_file_url($file, true) . '" width="100">';
				}
				echo $file['subject']; ?></a>
		</td>
		<td data-order="<?php echo $file['filetype']; ?>"><?php echo $file['filetype']; ?></td>
		<td>
			<?php if ($file['staffid'] != 0) {
				$_data = '<a href="' . admin_url('staff/profile/' . $file['staffid']) . '">' . staff_profile_image($file['staffid'], array(
						'staff-profile-image-small'
					)) . '</a>';
				$_data .= ' <a href="' . admin_url('staff/member/' . $file['staffid']) . '">' . get_staff_full_name($file['staffid']) . '</a>';
				echo $_data;
			}
			?>
		</td>
		<td data-order="<?php echo $file['dateadded']; ?>"><?php echo _dt($file['dateadded']); ?></td>
		<td>
			<?php if ($file['staffid'] == get_staff_user_id() || has_permission('products', '', 'delete')) { ?>
				<a href="#" onclick="doDeleteFile(<?= $file['id'] ?>); return false;"
				   class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
			<?php } ?>
		</td>
	</tr>
<?php } ?>