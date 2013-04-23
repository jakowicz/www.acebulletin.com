<?php

/**
 * UserTable
 *
 * Used to gather User records
 * 
 * @author Simon Jakowicz
 */

class UserTable extends Base_Table {
	
	/**
	 * Create and return a User object based on User ID provided
	 */

	const TABLE = 'users';
	
	public static function getInstance($id = null) {
		
		if($id > 0) {

			// load database connection
			$dbc = Database::instance();

			// find user
			$get_user = $dbc->prepare("SELECT * FROM users WHERE id=:id");
			$get_user->execute(array(':id' => (int) $id));
			$user = $get_user->fetchObject('User');
			
			// if the user record has been deleted return a blank object
			if(!is_object($user)) {
				$user = new User();
			}

		} else {
			$user = new User();
		}

		return $user;

	}

	
}
