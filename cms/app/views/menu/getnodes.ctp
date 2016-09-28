<?php
$data = array();
foreach ($nodes as $node){
	$data[] = array(
		"text"	=> $node['CmsMenu']['name'], 
		"id"	=> $node['CmsMenu']['id'],
		"cls"	=> "folder",
		"leaf"	=> ($node['CmsMenu']['lft'] + 1 == $node['CmsMenu']['rght'])
	);
}
echo $javascript->object($data);
?>