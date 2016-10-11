<?php
$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
// define table cells
$table = array(
    array('label' => __('No'), 'filter' => false),
		array('label' => __('BA Code'), 'filter' => true),
    array('label' => __('BA'), 'filter' => true),
		array('label' => __('Device ID'), 'filter' => true),
		array('label' => __('Devicel Model'), 'filter' => true),
		array('label' => __('Customer Name'), 'filter' => true),
    array('label' => __('Email'), 'filter' => true),
    array('label' => __('Mobile Number'), 'filter' => true),
		array('label' => __('Gender'), 'filter' => false),
		array('label' => __('Instagram'), 'filter' => false),
    array('label' => __('Current Cigarette'), 'filter' => false),
    array('label' => __('Product'), 'filter' => false),
    array('label' => __('Email Status'), 'filter' => false),
		array('label' => __('Device Date'), 'filter' => false)
);

$table3 = array(
	array('label' => __('Created'), 'filter' => false),
);

$table = array_merge($table, $table3);

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

		$tableRow = array(
			$no,
			$data['User']['code'],
			$data['User']['name'],
			$data[$ModelName]['device_id'],
			$data[$ModelName]['device_model'],
			$data[$ModelName]['name'],
      $data[$ModelName]['email'],
      $data[$ModelName]['mobile_phone'],
      $data[$ModelName]['gender'],
      $data[$ModelName]['instagram'],
      $data['CigaretteBrand']['name'],
      $data['CigaretteBrandProduct']['name'],
      $data[$ModelName]['SValid'],
      $this->Time->nice($data[$ModelName]['device_date']),
		);

		$tableRow3 = array(
			$data[$ModelName]['created']
		);

		$tableRow = array_merge($tableRow, $tableRow3);

		$this->PhpExcel->addTableRow($tableRow);
	}
}
// close table and output
$this->PhpExcel->addTableFooter()->output($filename);
?>
