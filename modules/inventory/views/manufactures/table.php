<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'name',
	'description',
	'status'
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'manufactures';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {
		$_data = $aRow[$aColumns[$i]];
		if ($aColumns[$i] == 'id') {
			$_data = '<a href="#" class="a_view_detail" onclick="editView(' . $aRow["id"] . '); return false;">' . $aRow['id'] . '</a>';
			$_data .= '<div class="row-options">';
			$_data .= '<a href="#" onclick="editView(' . $aRow["id"] . '); return false;">' . _l('view') . '</a>';

			if (has_permission('manufactures', '', 'delete')) {
				$_data .= ' | <a href="#" onclick="deleteView(' . $aRow["id"] . '); return false;" class="text-danger ">' . _l('delete') . '</a>';
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
