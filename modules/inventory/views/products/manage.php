<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/inventory/assets/blm.css'); ?>">
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div id="manage_table_id" class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php if (has_permission('products', '', 'create')) { ?>
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
							_l('Name'),
							_l('Manufacture'),
							_l('Description'),
							_l('Quantity'),
							_l('Create Date'),
							_l('Status'),
						), 'products'); ?>
					</div>
				</div>
			</div>

			<div id="div_product_detail_id" class="col-md-12" style="background-color: white;">
				<div class="panel_s">
					<div class="panel-body" id="product_detail_id">
						<?php /*$this->load->view('products/product_detail_data') */ ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$CI = &get_instance();
?>
<?php $this->load->view('inventory/products/includes/modals') ?>
<?php init_tail(); ?>

<script src="<?php echo base_url('modules/inventory/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/inventory/assets/products.js'); ?>"></script>
<script>
    $(function () {
        initDataTable('.table-products', window.location.href, [7], [7]);
        $('.table-products').DataTable().on('draw', function () {

        });
        $('#div_product_detail_id').hide();
    });


    function createView() {
        $('#div_modal_create').html("");
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        var gurl = admin_url + 'inventory/products/create_view?rtype=json&' + token_name + '=' + token_value;
        var purl = admin_url + 'inventory/products/create?rtype=json';
        simpleCUDModalUpload(
            '#div_modal_create',
            '#id_create_view_form',
            '#btn_create_view',
            gurl,
            purl,
            function (res) {
                $('#div_modal_create').html(res.data_template);
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
        var gurl = admin_url + 'inventory/products/edit_view?rtype=json&' + token_name + '=' + token_value + "&id=" + id;
        var purl = admin_url + 'inventory/products/edit?rtype=json';
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
        var gurl = admin_url + 'inventory/products/delete_view?rtype=json&' + token_name + '=' + token_value + "&id=" + id;
        var purl = admin_url + 'inventory/products/delete?rtype=json';
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


    function doDeleteFile(fileId) {
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        simpleAjaxPostUpload(
            admin_url + 'inventory/products/remove_file/?rtype=json&id=' + fileId + '&' + token_name + '=' + token_value + '&product_id=' + $('#product_id').val(),
            '#product-files-upload',
            function (res) {
                $('#images_list').html(res.view_file);
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

    function saveFile() {
        var token_name = '<?=$CI->security->get_csrf_token_name();?>';
        var token_value = '<?=$CI->security->get_csrf_hash();?>';
        simpleAjaxPostUpload(
            admin_url + 'inventory/products/upload_file/?rtype=json&' + token_name + '=' + token_value + '&product_id=' + $('#product_id').val(),
            '#product-files-upload',
            function (res) {
                $('#images_list').html(res.view_file);
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


    function view_product_file(id) {
        $('#product_file_data').empty();
        $("#product_file_data").load(admin_url + 'products/file/' + id, function (response, status, xhr) {
            if (status == "error") {
                alert_float('danger', xhr.statusText);
            }
        });
    }

</script>
</body>
</html>
