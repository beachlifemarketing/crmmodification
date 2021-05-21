<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div id="manage_table_id" style="width: 100%">
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

            <div id="div_mestimate_detail_id" style="display: none">
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
echo form_hidden('mestimate_id_view');
echo form_close();
?>

<?php init_tail(); ?>

<script src="<?php echo base_url('modules/mestimates/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/mestimates/assets/mestimates.js'); ?>"></script>
<script>
    $(function () {
        initDataTable('.table-mestimates', window.location.href, [6], [6]);
        $('.table-mestimates').DataTable().on('draw', function () {

        })
    });


    function showDetailMestimate(id) {
        var contentToggle = 0;
        if (contentToggle == 0) {
            $('#manage_table_id').animate({
                width: '50%'
            });
            $("#div_mestimate_detail_id").slideDown("slow");
            $('#div_mestimate_detail_id').animate({
                width: '50%',
                float: 'left'
            });
            contentToggle = 1;
        } else if (contentToggle == 1) {
            $('#manage_table_id').animate({
                width: '100%'
            });
            $("#div_mestimate_detail_id").slideUp("slow");
            $('#div_mestimate_detail_id').animate({
                width: '0',
            });
            contentToggle = 0;
        }
        document.getElementsByName("mestimate_id_view")[0].value = id;
        var url = admin_url + 'mestimates/show_detail?rtype=json';
        simpleAjaxPostUpload(
            url,
            '#id_to_view_form',
            function (res) {
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
