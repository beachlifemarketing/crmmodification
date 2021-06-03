<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
	'order_id',
	'company',
	'order_quantity',
	'create_order_date',
	'due_order_date',
	'order_status'
];

$sIndexColumn = 'order_id';
$sTable = db_prefix() . 'orders';

$join[] = 'INNER JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'orders.order_client_id';


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {
		$_data = $aRow[$aColumns[$i]];
		if ($aColumns[$i] == 'order_id') {
			$_data = '<a href="#" class="a_view_detail" onclick="editView(' . $aRow["order_id"] . '); return false;">' . $aRow['order_id'] . '</a>';
			$_data .= '<div class="row-options">';
			$_data .= '<a href="#" onclick="editView(' . $aRow["order_id"] . '); return false;">' . _l('view') . '</a>';

			if (has_permission('orders', '', 'delete')) {
				$_data .= ' | <a href="#" onclick="deleteView(' . $aRow["order_id"] . '); return false;" class="text-danger ">' . _l('delete') . '</a>';
			}
			$_data .= '</div>';
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
