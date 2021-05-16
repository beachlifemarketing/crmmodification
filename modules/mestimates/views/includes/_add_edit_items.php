<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="table-responsive s_table">

        <table id="estimate_details" width="100%" class="table table-striped">
            <thead>
            <tr>
                <th width="12%"><?php echo _l('are'); ?></th>
                <th width="10%"><?php echo _l('size'); ?></th>
                <th><?php echo _l('description'); ?></th>
                <th width="8%"><?php echo _l('qty'); ?></th>
                <th width="10%"><?php echo _l('unix_px'); ?></th>
                <th width="10%"><?php echo _l('duration'); ?></th>
                <th width="12%"><?php echo _l('amount'); ?></th>
                <th width="8%">&nbsp;</th>
            </tr>
            </thead>
            <tbody id="estimate_details_body">

            <?php
            $details = isset($details) ? $details : [];
            if (count($details) == 0) {
                ?>
                <tr class="tr_parent">
                    <td>
                        <input name="detail[are][]" type="text" placeholder="<?php echo _l('are'); ?>"
                               class="col-xs-12 col-sm-12 center"></td>
                    <td><input name="size[0]" type="text" placeholder="<?php echo _l('size'); ?>"
                               class="col-xs-12 col-sm-12 center"></td>
                    <td><textarea name="detail[description][]"
                                  class="col-xs-12 col-sm-12 description ui-autocomplete-input"
                                  autocomplete="off"></textarea></td>
                    <td><input name="detail[qty][]" type="number" placeholder="0" class="col-xs-12 col-sm-12 center"
                               onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                                class="quantity_uom_1"></span></td>
                    <td><input name="detail[unix][]" type="number" placeholder="0.00" class="col-xs-12 col-sm-12 right"
                               onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                                class="quantity_uom_1"></span></td>
                    <td><input name="detail[duration][]" type="text" placeholder="<?php echo _l('duration'); ?>"
                               class="col-xs-12 col-sm-12 center"
                               onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                        ></span></td>
                    <td><input name="detail[amount][]" type="text" placeholder="0.00"
                               class="col-xs-12 col-sm-12 right"
                               readonly="readonly"></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-xs" title="<?php echo _l('add'); ?>"
                                onclick="addRow($(this))"><i
                                    class="ace-icon fa fa-plus bigger-100 icon-only"></i></button>&nbsp;
                        <button type="button" class="btn btn-danger btn-xs hidden button_clone"
                                title="<?php echo _l('remove'); ?>"
                                onclick="removeRow($(this))">
                            <i
                                    class="ace-icon fa fa-minus bigger-100 icon-only"></i></button>
                        <input type="hidden" class="hidden" name="detail_id[0]" value="">
                    </td>
                </tr>
                <?php
            } else {
                for ($i = 0; $i < count($details); $i++) {
                    $detail = $details[$i];
                    ?>

                    <tr class="tr_parent">
                        <td><input value="<?= $detail->area ?>" name="detail[are][]" type="text"
                                   placeholder="<?php echo _l('are'); ?>"
                                   class="col-xs-12 col-sm-12 center"></td>
                        <td><input value="<?= $detail->size ?>" name="detail[size][]" type="text"
                                   placeholder="<?php echo _l('size'); ?>"
                                   class="col-xs-12 col-sm-12 center"></td>
                        <td><textarea value="<?= $detail->description ?>" name="detail[description][]"
                                      class="col-xs-12 col-sm-12 description ui-autocomplete-input"
                                      autocomplete="off"></textarea></td>
                        <td><input value="<?= $detail->qty_unit ?>" name="detail[qty][]" type="number" placeholder="0"
                                   class="col-xs-12 col-sm-12 center"
                                   onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                                    class="quantity_uom_1"></span></td>
                        <td><input value="<?= $detail->px_unit ?>" name="detail[unix][]" type="number"
                                   placeholder="0.00"
                                   class="col-xs-12 col-sm-12 right"
                                   onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                                    class="quantity_uom_1"></span></td>
                        <td><input value="<?= $detail->duration_unit ?>" name="detail[duration][]" type="text"
                                   placeholder="<?php echo _l('duration'); ?>"
                                   class="col-xs-12 col-sm-12 center"
                                   onkeyup="computeLineAmount()" onchange="computeLineAmount()"><br><span
                            ></span></td>
                        <td><input value="<?= $detail->amount ?>" name="detail[amount][]" type="text"
                                   placeholder="0.00"
                                   class="col-xs-12 col-sm-12 right"
                                   readonly="readonly"></td>
                        <td><input type="text" placeholder="0.00" class="col-xs-12 col-sm-12 right"
                                   readonly="readonly"></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-xs" title="<?php echo _l('add'); ?>"
                                    onclick="addRow($(this))"><i
                                        class="ace-icon fa fa-plus bigger-100 icon-only"></i></button>&nbsp;
                            <?php
                            if ($i > 0) {
                                ?>
                                <button type="button" class="btn btn-danger btn-xs" title="<?php echo _l('remove'); ?>"
                                        onclick="removeRow($(this))">
                                    <i class="ace-icon fa fa-minus bigger-100 icon-only"></i></button>
                                <?php
                            } else {
                                ?>
                                <button type="button" class="btn btn-danger btn-xs hidden button_clone"
                                        title="<?php echo _l('remove'); ?>"
                                        onclick="removeRow($(this))">
                                    <i class="ace-icon fa fa-minus bigger-100 icon-only"></i></button>
                                <?php
                            }
                            ?>
                            <input type="hidden" class="hidden" name="detail[id][]"
                                   value="<?= $details[$i]->id ?>">
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6" style="text-align: right"><?php echo _l('sub_total'); ?></td>
                <td><input value="" type="text" name="sub_total" id="sub_total" placeholder="0.00"
                           class="col-xs-12 col-sm-12 center"
                           readonly="readonly"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right"><?php echo _l('discount'); ?></td>
                <td><input type="number" id="discount" name="discount" placeholder="0.00"
                           onkeyup="computeLineAmount()" onchange="computeLineAmount()"
                           class="col-xs-12 col-sm-12 center"></td>
                <td><input type="text" id="discount_val" name="discount_val" placeholder="0.00"
                           class="col-xs-12 col-sm-12 center"
                           readonly="readonly"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right"><?php echo _l('total'); ?></td>
                <td><input value="" type="text" id="total" name="total" placeholder="0.00"
                           class="col-xs-12 col-sm-12 center"
                           readonly="readonly"></td>
                <td></td>
            </tr>

            <tr>
                <td colspan="4" style="text-align: right"></td>
                <td style="text-align: right"><input type="text" name="paid_by_customer_text"
                                                     placeholder="<?php echo _l('Total paid by customer'); ?>"
                                                     class="col-xs-12 col-sm-12 center"></td>
                <td><input onkeyup="computeLineAmount()" id="paid_by_customer_percent" onchange="computeLineAmount()"
                           type="number" name="paid_by_customer_percent" placeholder="percent"
                           class="col-xs-12 col-sm-12 center"></td>
                <td><input type="text" name="balance_due" placeholder="0.00"
                           id="balance_due" class="col-xs-12 col-sm-12 center" readonly="readonly"></td>
                <td></td>
            </tr>

            <tr>
                <td colspan="5" style="text-align: right"></td>
                <td><input type="text" name="balance_due_text"
                           placeholder="<?php echo _l('Balance Due (Insurance Billing)'); ?>"
                           class="col-xs-12 col-sm-12 center"></td>
                <td><input name="balance_due_val" type="text" readonly="readonly" placeholder="0.00"
                           id="balance_due_val"
                           class="col-xs-12 col-sm-12 center"></td>
                <td></td>
            </tr>
            </tfoot>
        </table>

    </div>
    <div id="removed-items"></div>
</div>
<script type="text/javascript">

</script>