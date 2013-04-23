<?php

class Controller extends Base_Controller {

	public function noHtmlLoad() {
		$captcha = new Captcha('image');
		$captcha->load();
	}

}