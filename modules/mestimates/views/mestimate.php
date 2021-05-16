<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content" id="id_content_mestimate">
        <?php $this->load->view('mestimates/includes/mestimate_data'); ?>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        validate_estimate_form();
        init_ajax_mestimate_search_by_customer_id();
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
    });

    // Ajax mestimate search but only for specific customer
    function init_ajax_mestimate_search_by_customer_id(selector) {
        selector = typeof (selector) == 'undefined' ? '#mestimate_id.ajax-search' : selector;
        init_ajax_search('mestimate', selector, {
            customer_id: function () {
                return $('#client_id').val();
            }
        });
    }
</script>

<script type="text/javascript">
    function selectClient() {
        var clientId = $('#client_id').val();
        $("#hid_client_id").val(clientId);
        simpleAjaxPostUpload(
            admin_url + 'mestimates/mestimate?rtype=json',
            '#id_content_mestimate',
            function (res) {
                $('#div_address').html(res.view_address);
                $('#row_file_mestimates').html(res.view_file);
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
        //window.location.href = admin_url + 'mestimates/mestimate/' + $('input[name="mestimate_id"]').val() + '/' + clientId;
    }

    function saveMestimate() {
        var url = admin_url + 'mestimates/estimate_do/';
        simpleAjaxPostUpload(
            url, '#id_content_mestimate',
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

    function saveTemplateMestimate() {

    }

    function computeLineAmount() {
        var sub_total = 0;
        var discount = $('#discount').val();
        var paid_by_customer_percent = $('#paid_by_customer_percent').val();
        $('input[name="detail[amount][]"]').each(function () {
            var qty = $(this).closest('tr').children('td:eq(3)').children('input').val();
            var unix = $(this).closest('tr').children('td:eq(4)').children('input').val();
            var amount = qty * unix;
            $(this).val(amount);
            sub_total += amount;
        });
        $('#sub_total').val(parseFloat(sub_total).toFixed(2));
        var total_discount = (sub_total * discount) / 100;
        var total_affter_discount = sub_total - total_discount;
        var total_due_amount = (total_affter_discount * paid_by_customer_percent) / 100;

        $('#balance_due').val(parseFloat(total_due_amount).toFixed(2));
        $('#discount_val').val(parseFloat(total_discount).toFixed(2));
        $('#total').val(parseFloat(total_affter_discount).toFixed(2));
        $('#balance').val(parseFloat(total_affter_discount - total_due_amount).toFixed(2));
    }

    function addRow(element) {
        var $tr = element.closest('.tr_parent');
        var $clone = $tr.clone();
        $clone.find('.button_clone').removeClass('hidden');
        $clone.find(':text').val('');
        $tr.after($clone);
    }

    function removeRow(element) {
        var $tr = element.closest('.tr_parent');
        $tr.remove();
    }

</script>

</body>
</html>