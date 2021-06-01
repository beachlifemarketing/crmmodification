<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/inventory/assets/blm.css'); ?>">
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div id="manage_table_id" class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php if (has_permission('manufactures', '', 'create')) { ?>
							<div class="_buttons">
								<a href="#" onclick="createView(); return false;"
								   class="btn btn-info pull-left display-block"><?php echo _l('Create new'); ?></a>
							</div>
							<div class="clearfix"></div>
							<hr class="hr-panel-heading"/>
						<?php } ?>
						<?php render_datatable(array(
							'ID',
							_l('Name'),
							_l('Description'),
							_l('Status')
						), 'manufactures'); ?>
					</div>
				</div>
			</div>

			<div id="div_manufacture_detail_id" class="col-md-12" style="background-color: white;">
				<div class="panel_s">
					<div class="panel-body" id="manufacture_detail_id">
						<?php /*$this->load->view('manufactures/manufacture_detail_data') */ ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$CI = &get_instance();
?>
<?php $this->load->view('inventory/manufactures/includes/modals') ?>
<?php init_tail(); ?>

<script src="<?php echo base_url('modules/inventory/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/inventory/assets/manufactures.js'); ?>"></script>
<script>
    $(function () {
        initDataTable('.table-manufactures', window.location.href, [3], [3]);
        $('.table-manufactures').DataTable().on('draw', function () {

        });
        $('#div_manufacture_detail_id').hide();
    });


    function createView() {
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/manufactures/create_view?rtype=json&' + token_name + '=' + token_value;
        var purl = admin_url + 'inventory/manufactures/create?rtype=json';
        simpleCUDModalUpload(
            '#div_modal_create',
            '#id_create_view_form',
            '#btn_create_view',
            gurl,
            purl,
            function (res) {
                $('#div_modal_create').modal('toggle');
                $('.btn-dt-reload').click();
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }

    function editView(id) {
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/manufactures/edit_view?rtype=json&' + token_name + '=' + token_value  + "&id=" + id;
        var purl = admin_url + 'inventory/manufactures/edit?rtype=json';
        simpleCUDModalUpload(
            '#div_modal_create',
            '#id_create_view_form',
            '#btn_create_view',
            gurl,
            purl,
            function (res) {
                $('#div_modal_create').modal('toggle');
                $('.btn-dt-reload').click();
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }

    function deleteView(id) {
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/manufactures/delete_view?rtype=json&' + token_name + '=' + token_value + "&id=" + id;
        var purl = admin_url + 'inventory/manufactures/delete?rtype=json';
        simpleCUDModalUpload(
            '#div_modal_create',
            '#id_delete_view_form',
            '#btn_delete_view',
            gurl,
            purl,
            function (res) {
                $('#div_modal_create').modal('toggle');
                $('.btn-dt-reload').click();
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }
</script>
</body>
</html>
