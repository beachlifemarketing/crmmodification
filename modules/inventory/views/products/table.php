<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
	'product_id',
	'product_code',
	'product_name',
	'name',
	'product_description',
	'quantity',
	'date_create',
	'product_status'
];

$sIndexColumn = 'product_id';
$sTable = db_prefix() . 'products';

$join[] = 'INNER JOIN ' . db_prefix() . 'manufactures ON ' . db_prefix() . 'manufactures.id = ' . db_prefix() . 'products.manufacturer_id';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {
		$_data = $aRow[$aColumns[$i]];
		if ($aColumns[$i] == 'product_id') {
			$_data = '<a href="#" class="a_view_detail" onclick="editView(' . $aRow["product_id"] . '); return false;">' . $aRow['product_id'] . '</a>';
			$_data .= '<div class="row-options">';
			$_data .= '<a href="#" onclick="editView(' . $aRow["product_id"] . '); return false;">' . _l('view') . '</a>';

			if (has_permission('products', '', 'delete')) {
				$_data .= ' | <a href="#" onclick="deleteView(' . $aRow["product_id"] . '); return false;" class="text-danger ">' . _l('delete') . '</a>';
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
