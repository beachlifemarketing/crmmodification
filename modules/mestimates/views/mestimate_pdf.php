<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

    <title>Union Restoration Inc</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/builds/vendor-admin.css?v=2.8.4'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/mestimates/assets/blm.css'); ?>">
    <style>
        table td {
            border: 1px solid black;
        }
    </style>
</head>

<div class="clearfix"></div>
<hr class="hr-panel-heading"/>
<br/>
<table style="width: 100%">
    <tr>
        <td>
            <?php
            $path = 'uploads/company/' . get_option('company_logo');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <img width="180" height="110" src="<?= $base64 ?>"/><br/>
                <?php
            }
            ?>

            <b><?= get_option('companyname'); ?></b>
            <b><a href="<?= get_option('main_domain'); ?>"><?= get_option('main_domain'); ?></a></b>
        </td>
        <td>

        </td>
        <td>
            <h3>Customer Information</h3>
            <table id="tbl_info_company" style="width: 100%;">
                <tr>
                    <td>Job #:</td>
                    <td><?= (isset($mestimate) ? $mestimate->job_number : '') ?></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><?= (isset($contact) ? $contact->name = "firstname" : '') ?> <?= (isset($contact) ? $contact->name = "lastname" : '') ?></td>
                </tr>
                <tr>
                    <td>Phone #:</td>
                    <td><?= (isset($contact) ? $contact->phonenumber : '') ?></td>
                </tr>
                <tr>
                    <td>Claim #:</td>
                    <td><?= (isset($mestimate) ? $mestimate->job_number : '') ?></td>
                </tr>
                <tr>
                    <td>Policy #:</td>
                    <td><?= (isset($mestimate) ? $mestimate->claim_number : '') ?></td>
                </tr>

                <tr>
                    <td>Property Address:</td>
                    <td><?= (isset($client) ? $client->company : '') ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?= (isset($contact) ? $contact->email : '') ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br/>
<table style="width: 100%" id="tbl_info_company_2">
    <tr style="background-color: lightgoldenrodyellow">
        <td>Representative</td>
        <td>Date</td>
        <td>Payment Options</td>
        <td>Due Date</td>
    </tr>
    <tr>
        <td><?= (isset($mestimate) ? $mestimate->representative : '') ?></td>
        <td><?= (isset($mestimate) ? $mestimate->date : '') ?></td>
        <td><?= (isset($mestimate) ? $mestimate->pymt_option : '') ?></td>
        <td><?= (isset($mestimate) ? $mestimate->due_date : '') ?></td>
    </tr>
</table>
<br/>
<table style="width: 100%">
    <tr>
        <td style="text-align: left; font-weight: bold">Receipt for
            Services
        </td>
        <td style="text-align: right; font-weight: bold">License No:
            MRSR2240
        </td>
    </tr>
</table>
<br/>
<table style="width: 100%" class="table items items-preview estimate-items-preview"
       data-type="estimate" border="1">
    <thead>
    <tr style="background-color: #0f1f2f; color: white">
        <th>AREA</th>
        <th>SIZE</th>
        <th>DESCRIPTION</th>
        <th>QUANTITY</th>
        <th>UNIT PRICE ($)</th>
        <th>DURATION</th>
        <th>AMOUNT ($)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i = 0; $i < count($details); $i++) {
        $detail = $details[$i];
        ?>
        <tr class="tr_parent">
            <td><?= $detail['area'] ?></td>
            <td><?= $detail['size'] ?></td>
            <td><?= $detail['description'] ?></td>
            <td><?= $detail['qty_unit'] ?></td>
            <td><?= $detail['px_unit'] ?></td>
            <td><?= $detail['duration'] ?></td>
            <td><?= $detail['amount'] ?></td>
        </tr>
        <?php
    } ?>

    </tbody>
</table>
<br/>
<table style="width: 100%" class="table text-right" border="1">
    <tbody>
    <tr id="subtotal">
        <td><span class="bold">REMEDIATION</span>
        </td>
        <td class="subtotal">
            <?= (isset($mestimate) && isset($mestimate->sub_total)) ? $mestimate->sub_total : 0.00 ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="bold">DISCOUNT <?= (isset($mestimate) && isset($mestimate->discount)) ? $mestimate->discount : 0.00 ?></span>
        </td>
        <td class="total">
            <?= (isset($mestimate) && isset($mestimate->discount_val)) ? $mestimate->discount_val : 0.00 ?>
        </td>
    </tr>
    <tr>
        <td><span class="bold">TOTAL</span>
        </td>
        <td class="total">
            <?= (isset($mestimate) && isset($mestimate->total)) ? $mestimate->total : 0.00 ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="bold">PERCENTAGE PAID <?= (isset($mestimate) && isset($mestimate->paid_by_customer_percent)) ? $mestimate->paid_by_customer_percent : 0.00 ?></span>
        </td>
        <td class="total">
            <?= (isset($mestimate) && isset($mestimate->paid_by_customer_text)) ? $mestimate->paid_by_customer_text : "" ?>
        </td>
    </tr>
    <tr>
        <td><span class="bold">PAID IN FULL -</span>
        </td>
        <td class="total">
            <?= (isset($mestimate) && isset($mestimate->balance_due_val)) ? $mestimate->balance_due_val : "" ?>
        </td>
    </tr>
    </tbody>
</table>
<script src="<?php echo base_url('modules/mestimates/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/mestimates/assets/mestimates.js'); ?>"></script>
</body>
</html>