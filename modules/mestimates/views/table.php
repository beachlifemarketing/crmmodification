<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'job_number',
    'claim_number',
    'lic_number',
    'sub_total',
    'due_date',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'mestimates';

$join = ['INNER JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'mestimates.client_id'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], [db_prefix() . 'mestimates.id as id']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'job_number') {
            $_data = '<a href="' . admin_url('mestimates/mestimate/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('mestimates/mestimate/' . $aRow['id']) . '">' . _l('view') . '</a>';

            if (has_permission('mestimates', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('mestimates/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'date' || $aColumns[$i] == 'due_date') {
            $_data = _d($_data);
        }
        $row[] = $_data;
    }
    ob_start();
    ?>
    <?php
    $progress = ob_get_contents();
    ob_end_clean();
    $row[] = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
