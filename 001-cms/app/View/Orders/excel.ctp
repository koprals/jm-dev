<?php
$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);

// define table cells
$table = array(
    array('label' => __('No'), 'filter' => false),
    array('label' => __('ID'), 'filter' => true),
    array('label' => __('Name'), 'filter' => true),
    array('label' => __('Type'), 'filter' => false),
	array('label' => __('Service')),
	array('label' => __('Service Date')),
	array('label' => __('Service Time')),
	array('label' => __('Total')),
	array('label' => __('Status'))
);

// add heading with different font and bold text
$this->PhpExcel->addTableHeader($table, array('name' => 'Cambria', 'bold' => true));

// add data
if(!empty($data))
{
	$count = 0;
	foreach ($data as $data)
	{
		$count++;
		$no		=	(($page-1)*$viewpage) + $count;
		$this->PhpExcel->addTableRow(array(
			$no,
			$data[$ModelName]['order_id_display'],
			$data[$ModelName]['fullname'],
			$data["OrderType"]['name'],
			$data["Service"]['name'],
			date("d M Y",strtotime($data[$ModelName]['date_order'])),
			$data[$ModelName]['time_order'],
			"Rp ".number_format($data[$ModelName]['total'],0,null,","),
			$data['OrderStatus']['name']
		));
	}
}
// close table and output
$this->PhpExcel->addTableFooter()->output($filename);
?>