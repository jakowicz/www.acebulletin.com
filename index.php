<?php 

// site core setup
require('app/bootstrap/setup.inc.php');

// decide which page to load
$route = System::load('router')->findRouteByPath($_GET['page']);

// if route wasn't found load 404
if(!$route) { 
	$route = System::load('router')->findRouteByName('404'); 
}

// go check out what this page is all about
require_once($route['controller_path']);

$controller = new Controller();

if(method_exists($controller, "noHtmlLoad")) {
	$controller->noHtmlLoad();
} else {
	$controller->load();
	if(Templater::exists('layout', $route['component'])) {
		Templater::render(array('contents' => $controller->getContent()), 'layout', $route['component']);
	} else {
		Templater::render(array('contents' => $controller->getContent()), 'layout', 'Base');
	}
}