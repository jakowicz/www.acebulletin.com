<?php

function componentAndModuleAutoload($class) {

	$locations = array(
		'components' => array(
			array(
				'@component@',
				'@model@'
			), 
			'components/@component@/models/@model@.inc.php'
		),
		'modules' => array(
			array(
				'@module@',
				'@class@'
			),
			'modules/@module@/classes/@class@.inc.php'
		),
	);
	
	foreach ($locations as $location => $location_data) {

		$components = scandir($location);
		foreach($components as $component) {
			
			// ignore . and ..
			if(!in_array($component, array('.', '..'))) {
				
				$class_file = str_replace($location_data[0], array($component, $class), $location_data[1]);
	
				// check if model exists
				if(file_exists($class_file)) {
					require_once($class_file);
				}
				
			}
		}
	}
}

// register all autloaders
spl_autoload_register('componentAndModuleAutoload');

// include misc functions file
require_once('functions.inc.php');

// init system
System::init();

// saves session error messages to a static class attribute
Messages::setAll(Session::getErrors());