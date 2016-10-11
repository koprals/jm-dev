<?php
App::uses('Model', 'Model');
class AppModel extends Model {
	public function setDatabase($database, $datasource = 'default')
	{
		$nds = $datasource . '_' . $database;      
		$db  = &ConnectionManager::getDataSource($datasource);
		
		$db->setConfig(array(
			'name'       => $nds,
			'database'   => $database,
			'persistent' => false
		));
		
		if ( $ds = ConnectionManager::create($nds, $db->config) ) {
			$this->useDbConfig  = $nds;
			$this->cacheQueries = false;
			return true;
		}	
		return false;
	}
}
