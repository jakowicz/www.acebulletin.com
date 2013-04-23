<?php

/**
 * System
 *
 * Site core functionality & configuration
 * 
 * @author Simon Jakowicz
 */


class System {

	private static $config  = null;
	
	/**
	 * Setup framework
	 * Load config files into the object
	 * Set ini value
	 * Setup a PHP error handler
	 * Start the users session
	 */
	
	public static function init() {
		self::setupBaseConfiguration();
		self::setTimeZone();
		self::iniSet();
		self::setupErrorHandler();
		self::startSession();
	}
	
	/** 
	 * Load a sub system
	 */
		
	public static function load($sub) {
		$sub = ucfirst($sub);
		return new $sub();
	}

	/**
	 * Load data about the request into the object
	 * Load config files into the object
	 */
	
	private static function setupBaseConfiguration() {

		// server data
		self::set('host', $_SERVER['SERVER_NAME']);
		self::set('domain', 'http://' . $_SERVER['SERVER_NAME']);
		self::set('current_file', basename($_SERVER['PHP_SELF']));
		self::set('webroot', '/');
		self::set('web_address', self::get('domain') . self::get('webroot'));
		self::set('web_address_no_slash', substr(self::get('domain') . self::get('webroot'), 0, -1));
		self::set('root', $_SERVER['DOCUMENT_ROOT'] . self::get('webroot'));
		
		// folder locations
		self::set('modules', self::get('root') . 'modules/');
		self::set('config', self::get('root') . 'app/config/');
        self::set('temp', self::get('root') . 'runtime/');
        self::set('app', self::get('root') . 'app/');
		
		// errors
		self::set('error_log', self::get('temp') . 'errors/php.txt');

		self::parseIniConfig('system');
        self::parseIniConfig('source');
        self::parseIniConfig('custom');
        
    }
    
    /**
     * Set cookie and session expiry times
     */
     
    private static function iniSet() {
    	ini_set('session.use_cookies', true);
    	ini_set('session.cookie_lifetime', self::get('login_expiry', 24) * 3600);
    	ini_set('session.gc_maxlifetime', self::get('login_expiry', 24) * 3600); 
    }
    
    /**
     * Set the timezone of the application
     */
    
    private static function setTimeZone() {
	    date_default_timezone_set('UTC');
	}
    
    /**
	 * parse config from ini files into the object
	 */
    
    public static function parseIniConfig($filename, $module_name = null) {
        
        if($module_name) {
            $config_file = self::get('modules') . $module_name . '/config/' . $filename . '.ini';
        } else {
            $config_file = self::get('config') . $filename . '.ini';
        }
        
        if(file_exists($config_file)) {
            $config = parse_ini_file($config_file, true);
 
            if(isset($config[self::get('environment')])) {
                $config = $config[self::get('environment')];
            }

            foreach($config as $key => $value) {
                self::set($key, Ini::unescape($value));
            } 
        }
      
    }
    
    /**
	 * Setup a PHP error handler
	 */
	
	private static function setupErrorHandler() {
        set_error_handler(array('System', 'errorHandler'));
    }
    
    /**
	 * PHP error handler
	 * Don't show PHP errors in a production environment
	 */
	
	public static function errorHandler($errorNumber, $errorMessage, $errorFile, $errorLine) {
	
		// ignore E_NOTICE, E_STRICT and E_DEPRECATED
		if(!in_array($errorNumber, array(8,2048,8192))) {
	
			// set error log message
			$error = date("Y-m-d H:i:s") . ' ::: An error has occured in the file ' . $errorFile . ' on line ' . $errorLine . PHP_EOL . $errorMessage;
			
			// create folder if required
			if(!is_dir(dirname(self::get('error_log')))) {
				mkdir(dirname(self::get('error_log')), 0777);
			}
		
			// update error log
			error_log($error, 3, self::get('error_log'));
		
			if(self::get('environment') == 'production') {
				Templater::render(array('message' => 'We are currently experiencing a system error, an administrator has been notified and this will be looked into immediately. We are sorry for any inconvenience'), '_error_message', 'Base');
			} else {
				Templater::render(array('message' => $error), '_error_message', 'Base');
			}
		}
	}
    
	/**
	 * Start session
	 */
	
	private static function startSession() {
		session_name(str_replace(' ', '', self::get('site_name')));
		session_start();
	}
    
    /**
	 * Check if the system is live aka the production environment
	 */
	
	public static function live() {
		if(self::get('environment') == 'production') {
			return true;
		}
        return false;
	}
	
	/**
	 * locate the current pages JS file (if there is one)
	 */
	
	public static function getPageJSFile() {
		global $route;
		if(file_exists(self::get('root') . 'public/js/components/' . $route['component'] . '/' . $route['controller'] . '.js')) {
			return '/public/js/components/' . $route['component'] . '/' . $route['controller'] . '.js';
		}
		return false;
	}
	
	/**
	 * Set a config value
	 */
	
	public static function set($key, $value) {
		self::$config[$key] = $value;
	}
	
	/**
	 * Get a config value
	 */
	
	public static function get($key, $default = null) {
		return isset(self::$config[$key]) ? self::$config[$key] : $default;
	}
	
}