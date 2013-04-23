<?php

class Base_Controller {

	public function __construct() {
		ob_start();
	}
	
	public function getContent() {
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

}