<?php

$routes = array(
	'home' => array(
		'route' => '/',
		'controller' => 'home'
	),
	'hello' => array(
		'route' => '/hello/:name',
		'controller' => 'hello'
	)
);
