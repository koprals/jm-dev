<?php
$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);

// define table cells
$table = array(
    array('label' => __('No'), 'filter' => false),
    array('label' => __('ID'), 'filter' => true),
    array('label' => __('Nama'), 'filter' => true),
    array('label' => __('Alamat'), 'filter' => false),
	array('label' => __('Tgl Layanan')),
	array('label' => __('Jam Layanan')),
	array('label' => __('Total Shift')),
	array('label' => __('Total Maid')),
	array('label' => __('Harga')),
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
			$this->Text->Truncate($data[$ModelName]['address'],50,array("ending"=>"...")),
			date("d M Y",strtotime($data[$ModelName]['date_order'])),
			$data[$ModelName]['time_order'],
			$data[$ModelName]['total_shift'],
			$data[$ModelName]['total_maid'],
			"Rp ".number_format($data[$ModelName]['total'],0,null,","),
			$data['OrderStatus']['name']
		));
	}
}
// close table and output
$this->PhpExcel->addTableFooter()->output($filename,'Excel5');
?>