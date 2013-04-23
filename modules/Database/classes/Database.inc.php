<?php

/**
 * Database
 *
 * Provide connection to database
 * 
 * @author Simon Jakowicz
 */

class Database {
    
	private static $instance;
	private static $statements;
	
	public static function instance($credentials = null, $override = false) {
        
		if (!isset(self::$instance) || $override) {

			// set database credentials
			System::parseIniConfig('database', 'Database');

			if($override) {

				// used for validation only
				
				try {

					// if(!checkdnsrr($credentials['database_host'], 'ANY')) {
					// 	throw new PDOException('Invalid hostname');
					// }
				
					$dsn = 'mysql:dbname=' . $credentials['database_name'] . ';host=' . $credentials['database_host'];
					$instance = new PDO($dsn, $credentials['database_username'], $credentials['database_password']);

					return $instance;

				} catch (PDOException $e) {
					
					return false;

				}

			} else {

				// used for system connections
				
				try {

					// if(!checkdnsrr(System::get('database_host'), 'ANY')) {
					// 	throw new PDOException('Invalid hostname');
					// }

					$dsn = 'mysql:dbname=' . System::get('database_name') . ';host=' . System::get('database_host');
					self::$instance = new PDO($dsn, System::get('database_username'), System::get('database_password'));
				
				} catch (PDOException $e) { 
					echo 'Connection failed: ' . $e->getMessage();
				}

			}

		}
		
		return self::$instance;
	}
	
	public static function saveStatement($name, $sql) {
		$dbc = self::instance();
		if(!Database::isStatement($name)) {
			self::$statements[$name] = $dbc->prepare($sql);
		}
	}
	
	public static function getStatement($name) {
		return self::$statements[$name];
	}
	
	public static function isStatement($name) {
		return isset(self::$statements[$name]) ? true : false;
	}
	
}