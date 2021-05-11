<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="form-group mbot25 items-wrapper select-placeholder<?php if (has_permission('items', '', 'create')) {
    echo ' input-group-select';
} ?>">
    <div class="<?php if (has_permission('items', '', 'create')) {
        echo 'input-group input-group-select';
    } ?>">
        <div class="items-select-wrapper">
            <select name="item_select" class="selectpicker no-margin<?php if ($ajaxItems == true) {
                echo ' ajax-search';
            } ?><?php if (has_permission('items', '', 'create')) {
                echo ' _select_input_group';
            } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>"
                    data-live-search="false" multiple>
                <?php foreach ($items as $group_id => $_items) { ?>
                    <optgroup class="group_item" data-group-id="<?php echo $group_id; ?>"
                              label="<?php echo $_items[0]['group_name']; ?>"
                              style="font-weight: bold;cursor: pointer;">
                        <?php foreach ($_items as $item) { ?>
                            <option value="<?php echo $item['id']; ?>"
                                    data-subtext="<?php echo strip_tags(mb_substr($item['long_description'], 0, 200)) . '...'; ?>">
                                (<?php echo app_format_number($item['rate']);; ?>
                                ) <?php echo $item['description']; ?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
        </div>
        <?php if (has_permission('items', '', 'create')) { ?>
            <div class="input-group-addon">
                <a href="#" title="Accept" onclick="apply_list_item();return false;">
                    <i class="fa fa-check" aria-hidden="true"></i>
                </a>
            </div>
        <?php } ?>
        <?php if (has_permission('items', '', 'create')) { ?>
            <div class="input-group-addon">
                <a href="#" data-toggle="modal" data-target="#sales_item_modal">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        <?php } ?>

    </div>
</div>

<script type="text/javascript">
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    if (typeof (jQuery) != 'undefined') {
        initJs();
    } else {
        window.addEventListener('load', function () {
            var initItemsJsInterval = setInterval(function () {
                if (typeof (jQuery) != 'undefined') {
                    initJs();
                    clearInterval(initItemsJsInterval);
                }
            }, 100);
        });
    }

    function initJs() {
        let tr_first = $("tbody.ui-sortable").find("tr.main");
        if (tr_first == null) {
            return false;
        }
        let button_confirm_first = tr_first.find("button");
        if (button_confirm_first == null) {
            return false;
        }
    }

    function apply_list_item() {
        var itemids = $("#item_select").selectpicker('val');
        if (itemids.length < 1) {
            return false;
        }

        var i = 0;
        do {
            let tr_first = $("tbody.ui-sortable").find("tr.main");
            if (tr_first == null) {
                return false;
            }
            let button_confirm_first = tr_first.find("button");
            if (button_confirm_first == null) {
                return false;
            }
            requestGetJSON('invoice_items/get_item_by_id/' + itemids[i]).done(function (response) {
                clear_item_preview_values();

                $('.main textarea[name="description"]').val(response.description);
                $('.main textarea[name="long_description"]').val(response.long_description.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g, " "));

                _set_item_preview_custom_fields_array(response.custom_fields);

                $('.main input[name="quantity"]').val(1);

                var taxSelectedArray = [];
                if (response.taxname && response.taxrate) {
                    taxSelectedArray.push(response.taxname + '|' + response.taxrate);
                }
                if (response.taxname_2 && response.taxrate_2) {
                    taxSelectedArray.push(response.taxname_2 + '|' + response.taxrate_2);
                }

                $('.main select.tax').selectpicker('val', taxSelectedArray);
                $('.main input[name="unit"]').val(response.unit);

                var $currency = $("body").find('.accounting-template select[name="currency"]');
                var baseCurency = $currency.attr('data-base');
                var selectedCurrency = $currency.find('option:selected').val();
                var $rateInputPreview = $('.main input[name="rate"]');

                if (baseCurency == selectedCurrency) {
                    $rateInputPreview.val(response.rate);
                } else {
                    var itemCurrencyRate = response['rate_currency_' + selectedCurrency];
                    if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
                        $rateInputPreview.val(response.rate);
                    } else {
                        $rateInputPreview.val(itemCurrencyRate);
                    }
                }

                $(document).trigger({
                    type: "item-added-to-preview",
                    item: response,
                    item_type: 'item',
                });

                add_item_to_table(response,itemids[i],undefined);
            });

            i++;
        }
        while (i < itemids.length);
    }
</script>