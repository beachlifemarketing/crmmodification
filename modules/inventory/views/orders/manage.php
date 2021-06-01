<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/inventory/assets/blm.css'); ?>">
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div id="manage_table_id" class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php if (has_permission('orders', '', 'create')) { ?>
							<div class="_buttons">
								<a href="#" onclick="createView(); return false;"
								   class="btn btn-info pull-left display-block"><?php echo _l('Create new'); ?></a>
							</div>
							<div class="clearfix"></div>
							<hr class="hr-panel-heading"/>
						<?php } ?>
						<?php render_datatable(array(
							_l('ID'),
							_l('Code'),
							_l('Customer'),
							_l('Quantity'),
							_l('Create Date'),
							_l('Due Date'),
							_l('Status'),
						), 'orders'); ?>
					</div>
				</div>
			</div>

			<div id="div_order_detail_id" class="col-md-12" style="background-color: white;">
				<div class="panel_s">
					<div class="panel-body" id="order_detail_id">
						<?php /*$this->load->view('orders/order_detail_data') */ ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$CI = &get_instance();
?>
<?php $this->load->view('inventory/orders/includes/modals') ?>
<?php init_tail(); ?>

<script src="<?php echo base_url('modules/inventory/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/inventory/assets/orders.js'); ?>"></script>
<script>
    $(function () {
        initDataTable('.table-orders', window.location.href, [6], [6]);
        $('.table-orders').DataTable().on('draw', function () {

        });
        $('#div_order_detail_id').hide();
    });


    function createView() {
        $('#div_modal_create').html("");
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/orders/create_view?rtype=json&' + token_name + '=' + token_value;
        var purl = admin_url + 'inventory/orders/create?rtype=json';
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
        $('#div_modal_create').html("");
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/orders/edit_view?rtype=json&' + token_name + '=' + token_value + "&id=" + id;
        var purl = admin_url + 'inventory/orders/edit?rtype=json';
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
        $('#div_modal_create').html("");
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/orders/delete_view?rtype=json&' + token_name + '=' + token_value + "&id=" + id;
        var purl = admin_url + 'inventory/orders/delete?rtype=json';
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
