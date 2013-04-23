<?php

/**
 * DatabaseFixturesInstaller
 *
 * Install a bunch of fixtures into the database
 * 
 * @author Simon Jakowicz
 */

class DatabaseFixturesInstaller {

	private static $_fixtures = array( 
		
		"Query 1",
		"Query 2",
		"Query 3",
		
	);
    
	public static function install() {
        
		// get database connection
		$dbc = Database::instance();

		// process fixture
		foreach(self::$_fixtures as $sql) {
			$dbc->query($sql);
		}
		
	}
	
}