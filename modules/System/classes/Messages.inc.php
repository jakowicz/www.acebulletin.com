<?php

/**
 * Messages
 *
 * Used to deal with user based msgs
 * 
 * @author Simon Jakowicz
 */

class Messages {

	private static $msgs = array();
	
	/**
	 * set errors on load
	 */
	
	public static function setAll($errors = array()) {
		self::$msgs = $errors;
	}
	
	/**
	 * set an message
	 */
	
	public static function set($type, $val) {
		self::$msgs[$type] = $val;
	}
	
	/**
	 * get an error message
	 */
	
	public static function get($type) {
		return self::$msgs[$type];
	}
	
	/**
	 * return array of all messages
	 */
	
	public static function fetch() {
		return self::$msgs;
	}
	
	/**
	 * check if an error exists for what you want
	 */
	
	public static function exist($type = null) {
		
		if($type) {
			if(array_key_exists($type, self::$msgs)) {
				return true;
			} else {
				return false;
			}
		} else {
			return count(self::$msgs) > 0 ? true : false;
		}
	}
	
}
