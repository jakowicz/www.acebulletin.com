<?php

/**
 * DatabaseInstaller
 *
 * Process a bunch of queries to update the database to the latest revision
 * 
 * @author Simon Jakowicz
 */

class DatabaseInstaller {

	private static $_updates = array( 
					
		1 => array(
		
			"Query 1",
			"Query 2",
			"Query 3",
			
		),

		2 => array(
		
			"Query 1",
			"Query 2",
			"Query 3",
			
		),


	);
    
	public static function upgrade($first = false) {
        
		/* BEFORE YOU CAN EXECUTE THIS ACTION, A "_database" TABLE MUST EXIST
		
			CREATE TABLE IF NOT EXISTS `_database` (
			  `id` int(11) unsigned NOT NULL,
			  `version` int(11) unsigned NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `version` (`version`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8			
		*/
		
		$dbc = Database::instance();
		
		// get next upgrade - version number
		if($first) {
			$next_version = 1;
		} else {
			$next_version = self::_getNextVersion();
		}

		// process queries
		while(isset(self::$_updates[$next_version])) {
			
			// start a transaction for each upgrade and mark success as the default
			$dbc->beginTransaction();
			$success = true;
			foreach(self::$_updates[$next_version] as $sql) {
				// if the query is a success then keep going
				// if a query fails, rollback all changes for this upgrade and stop further updates
				if($dbc->exec($sql) === false) {
					$error = $dbc->errorInfo();
					Messages::set('error_update_version', $next_version);
					Messages::set('error_message', $error[0] . ' :: ' . $error[2]);
					$dbc->rollBack();
					break 2;
				}
			}
			
			// run all queries in this update version
			$dbc->commit();
			$next_version++;
		}
		
		// Update db version in system (Regardless of error), the version may have been updated but not all versions
		self::_setCurrentVersion($next_version-1);

		if(Messages::exist('error_update_version')) {
			return false;
		}

		return true;
		
	}
	
	/*
	 * Based on the current version of the schema find the next, in case of no schema make the next version #1
	 */
	
	private static function _getNextVersion() {
	    
		$dbc = Database::instance();

		$get_db_version = $dbc->prepare("SELECT * FROM _database WHERE id=1");
		$get_db_version->execute();
		if($get_db_version->rowCount() == 0) {
			$next_version = 1;
		} else {
			$db = $get_db_version->fetch(PDO::FETCH_ASSOC);
			$next_version = $db['version'] + 1;
		}

		return $next_version;
		
	}
	
	/*
	 * Update the databse with the current version
	 */
	
	private static function _setCurrentVersion($version) {
	    
		$dbc = Database::instance();
		$set_db_version = $dbc->prepare("INSERT INTO _database (id, version) VALUES (1, ?) ON DUPLICATE KEY UPDATE version=?");
		$set_db_version->execute(array($version, $version));
		return true;
		
	}

	
}