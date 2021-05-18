<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php render_datatable(array(
                            _l('template_name'),
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
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-mestimates', window.location.href, [6], [6]);
        $('.table-mestimates').DataTable().on('draw', function () {

        })
    });
</script>
</body>
</html>
