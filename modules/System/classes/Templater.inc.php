<?php

/**
 * Templater
 *
 * Used for loading templates
 * 
 * @author Simon Jakowicz
 */

class Templater {
    
	static public function exists($__view, $__component) {

		if(file_exists(System::get('root') . 'components/' . $__component . '/views/' . $__view . '.tpl.php')) {
			return true;
		}
		return false;
	}
	
	static public function render($variables = array(), $__view = null, $__component = null) {
	
			$__route = Router::getRoute();
	
			extract($variables);
	
			if(empty($__view)) {
				$__view = $__route['controller'];
			}
	
			if(empty($__component)) {
				$__component = $__route['component'];
			}
	
			require(System::get('root') . 'components/' . $__component . '/views/' . $__view . '.tpl.php');
		}

}