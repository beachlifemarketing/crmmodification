<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'template_name',
    'date',
    'representative',
    'sub_total',
    'discount',
    'balance_due',
    'total'

];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'mestimates';

$join[] = 'INNER JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'mestimates.staff_id';
$join[] = 'INNER JOIN ' . db_prefix() . 'clients ON  ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'mestimates.client_id';

$where[] = 'AND status = "template"';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'template_name') {
            /*$_data = '<a href="' . admin_url('mestimates/tpl/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('mestimates/tpl/' . $aRow['id']) . '">' . _l('view') . '</a>';*/

            if (has_permission('mestimates', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('mestimates/delete_template/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'start_date' || $aColumns[$i] == 'end_date') {
            $_data = _d($_data);
        } elseif ($aColumns[$i] == 'mestimate_type') {
            $_data = format_mestimate_type($_data);
        }
        $row[] = $_data;
    }
    ob_start();
    $progress = ob_get_contents();
    ob_end_clean();
    $row[] = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}