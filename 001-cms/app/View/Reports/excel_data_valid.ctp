<?php
$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);

// define table cells
$table = array(
    array('label' => __('No'), 'filter' => false),
    array('label' => __('BA Code'), 'filter' => true),
    array('label' => __('Name'), 'filter' => true),
    array('label' => __('Total Data Consumer'), 'filter' => false),
	  array('label' => __('Email Valid'), 'filter' => false),
    array('label' => __('Email Not Valid'), 'filter' => false),
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

    $baCode = $data['User']['code'];
    $baName = $data['User']['name'];

    $totalCustomer = 0;
    if(isset($matixAllCustomer[$data['User']['id']])) {
      $totalCustomer = $matixAllCustomer[$data['User']['id']];
    }

    $totalNotValid = 0;
    if(isset($matixAllNotValid[$data['User']['id']])) {
      $totalNotValid = $matixAllNotValid[$data['User']['id']];
    }

    $totalValid = 0;
    if(isset($matixAllValid[$data['User']['id']])) {
      $totalValid = $matixAllValid[$data['User']['id']];
    }

		$this->PhpExcel->addTableRow(array(
			$no,
			$baCode,
      $baName,
      $totalCustomer,
      $totalValid,
      $totalNotValid
		));
	}
}
// close table and output
$this->PhpExcel->addTableFooter()->output($filename);
?>
