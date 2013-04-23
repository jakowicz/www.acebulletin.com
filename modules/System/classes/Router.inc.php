<?php

/**
 * Router
 *
 * Search, generate and redirect to routes
 * 
 * @author Simon Jakowicz
 */


class Router {

	private static $routes = array();
	private static $route = array();
	private static $route_json_file;
	
	/**
	 * Return the last found route
	 */
	
	public static function getRoute() {
		return self::$route;
	}
	
	/**
	 * Return the last found routes data
	 */
	
	public static function getData() {
		return self::$route['data'];
	}
	
	/**
	 * discover all routes from cache or lookup
	 */
	
	private static function loadRoutes() {
	
		// check routes arn't already loaded
		if(empty(self::$routes)) {
		
			// set path of route cache
			self::$route_json_file = System::get('temp') . 'routes/routes.txt';
	
			// load from cache of system search
			if(file_exists(self::$route_json_file) && System::live()) {
				self::$routes = unserialize(file_get_contents(self::$route_json_file));
			} else {
				self::search();
			}
		}
	}
	
	/**
	 * Find a route by name
	 */
	
	public static function findRouteByName($name) {
		
		// load routes
		self::loadRoutes();
		
		// find a matching route
		foreach(self::$routes as $route) {

			if($route['name'] == $name) {

				// only set this as the route if this is initial load
				if(empty(self::$route)) {
					self::$route = $route;
				}

				return $route;
			}
		}
	}
	
	/**
	 * Find a route that matches the path given
	 */
	
	public static function findRouteByPath($find_route = '/') {

		$find_route = '/' . $find_route;

		// load routes
		self::loadRoutes();

		// find a matching route
		foreach(self::$routes as $route) {

			// find a route that matches the path given
			if(preg_match($route['route_regex'], $find_route, $matches)) {

				// assign route data
				unset($matches[0]);
				foreach (array_values($matches) as $key => $value) {
					$data[$route['data_variables'][$key]] = $value;
				}
				$route['data'] = $data;

				// only set this as the route if this is initial load
				if(empty(self::$route)) {
					self::$route = $route;
				}

				return $route;
			}
		}
	}
	
	/**
	 * Search for all route files
	 */
	
	private static function search() {


		$locations = array(
			System::get('root') . 'components/' => array(
				'routing' => System::get('root') . 'components/@area@/config/routing.php',
				'controllers' => System::get('root') . 'components/@area@/controllers/@controller@.php'
			),
			System::get('root') . 'modules/' => array(
				'routing' => System::get('root') . 'modules/@area@/config/routing.php',
				'controllers' => System::get('root') . 'modules/@area@/controllers/@controller@.php'
			)			
		);

		// search all locations
		foreach($locations as $search_path => $routing_file) {

			$components = scandir($search_path);

			foreach($components as $component) {
				// ignore . and ..
				if(!in_array($component, array('.', '..'))) {

					$actual_routing_file = str_replace('@area@', $component, $routing_file['routing']);

					// check if routing file exists
					if(file_exists($actual_routing_file)) {

						// load routes
						require($actual_routing_file);

						foreach ($routes as $name => &$route) {

							// set route name
							$route['name'] = $name;

							// create the actual path to the controller
							$route['controller_path'] = str_replace(
								array(
									'@area@',
									'@controller@'
								), 
								array(
									$component,
									$route['controller']
								), 
								$routing_file['controllers']
							);

					// create a regex for this path that me can match against
							$route['route_regex'] = '!^' . preg_replace('!(:[\w_]+)!', '(.+)', $route['route']) . '$!';

							// set the component of the route
							$route['component'] = $component;

							// gather the data variables for this route
							preg_match_all('!:([\w_]+)!', $route['route'], $data_blocks);
							if(count($data_blocks) > 0) {
								unset($data_blocks[0]);
								for($i=1; $i<=count($data_blocks); $i++) {
									$route['data_variables'] = $data_blocks[$i];
								}
							}

						}

						// merge routes
						self::$routes = array_merge(self::$routes, $routes);
					}
				}
			}
		}
   
		 self::cacheRoutes();
	}
	
	/**
	 * cache routes
	 */
	
	private static function cacheRoutes() {

		// create folder if required
		if(!is_dir(dirname(self::$route_json_file))) {
			mkdir(dirname(self::$route_json_file), 0777);
		}
	
		// write file
		file_put_contents(self::$route_json_file, serialize(self::$routes));

		return true;

	}
	
	/**
	 * generate route from name and variables
	 */
	
	public static function generate($route_name, $variables = array()) {

		$route = self::findRouteByName($route_name);
		$find_route = $route['route'];
		
		foreach ($variables as $placeholder => $value) {
			$find_route = str_replace(':' . $placeholder, $value, $find_route);
		}

		return $find_route;

	}
	
	/**
	 * redirect user based on route name and variables
	 */
	
	public static function redirect($route_name, $variables = array()) {
		$find_route = self::generate($route_name, $variables);
		header("location: " . $find_route);
		exit;
	}	

}