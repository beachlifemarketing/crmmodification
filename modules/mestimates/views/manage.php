<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/mestimates/assets/blm.css'); ?>">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div id="manage_table_id" class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (has_permission('mestimates', '', 'create')) { ?>
                            <div class="_buttons">
                                <a href="<?php echo admin_url('mestimates/mestimate'); ?>"
                                   class="btn btn-info pull-left display-block"><?php echo _l('new_mestimate'); ?></a>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                        <?php } ?>
                        <?php render_datatable(array(
                            'ID',
                            _l('job_number'),
                            _l('date'),
                            _l('representative'),
                            _l('sub_total'),
                            _l('discounts'),
                            _l('balance_due'),
                            _l('total')
                        ), 'mestimates'); ?>
                    </div>
                </div>
            </div>

            <div id="div_mestimate_detail_id" class="col-md-12" style="background-color: white;">
                <div class="panel_s">
                    <div class="panel-body" id="mestimate_detail_id">
                        <?php /*$this->load->view('mestimates/mestimate_detail_data') */ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo form_open('', array('id' => 'id_to_view_form'));
echo '<input type="hidden" name="mestimate_id_view" id="mestimate_id_view" >';
echo form_close();
?>
<?php init_tail(); ?>

<script src="<?php echo base_url('modules/mestimates/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/mestimates/assets/mestimates.js'); ?>"></script>
<script>
    $(function () {
        initDataTable('.table-mestimates', window.location.href, [7], [7]);
        $('.table-mestimates').DataTable().on('draw', function () {

        });
        $('#div_mestimate_detail_id').hide();
    });


    function showDetailMestimate(id) {
        $("#mestimate_id_view").val(id);
        var url = admin_url + 'mestimates/mestimate/' + id + '?rtype=json';
        simpleAjaxPostUpload(
            url,
            '#id_to_view_form',
            function (res) {
                $('#div_mestimate_detail_id').html(res.data_template);
                $('#manage_table_id').removeClass('col-md-12');
                $('#manage_table_id').addClass('col-md-6');
                $('#div_mestimate_detail_id').show();
                $('#div_mestimate_detail_id').addClass('col-md-6');
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
