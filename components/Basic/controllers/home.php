
<?php

class Controller extends Base_Controller {

	public function load() {

		// set page title
		System::set('page_title', 'Home');
		
		// set breadcrumb
		System::set('breadcrumb',  array(
			'Home' => null,
		));
		
		// render
		Templater::render();
	}

}