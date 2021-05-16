<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
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
                            _l('discount_percent'),
                            _l('balance_due'),
                            _l('total')
                        ), 'mestimates'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-mestimates', window.location.href, [6], [6]);
        $('.table-mestimates').DataTable().on('draw', function () {
            var rows = $('.table-mestimates').find('tr');
            $.each(rows, function () {
                var td = $(this).find('td').eq(6);
                var percent = $(td).find('input[name="percent"]').val();
                $(td).find('.mestimate-progress').circleProgress({
                    value: percent,
                    size: 45,
                    animation: false,
                    fill: {
                        gradient: ["#28b8da", "#059DC1"]
                    }
                })
            })
        })
    });
</script>
</body>
</html>