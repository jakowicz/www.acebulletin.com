<?php

class Controller extends Base_Controller {

	public function load() {
		
		System::set('page_title', 'Page Not Found');
		
		// set breadcrumb
		System::set('breadcrumb',  array(
			'404 Page Not Found' => null,
		));
		
		Templater::render(array(
			'title' => 'Ooops 404 error',
			'message' => "What have you done, the page you are looking for doesn't actually exist"			
		), '_generic_message');
	}

}