<?php

/**
 * Captcha
 *
 * Tests to make sure the user is human
 * 
 * @author Simon Jakowicz
 */

class Captcha {

	/**
	 * check captcha was successful
	 */
	
	static public function validate($captcha) {
		if(isset($_SESSION['captcha']) && $_SESSION['captcha'] != md5(strtoupper($captcha))) {
			Messages::set('captcha', 'Sorry the code you entered did not match the image');
		}
		unset($_SESSION['captcha']);
	}

	public $type = array();
	
	/**
	 * set the type of captcha to use
	 */
	
	public function __construct($type) {
		$this->type = $type;
	}
	
	/**
	 * determine what type to load and load it
	 */
	
	public function load() {
		switch($this->type) {
			case 'image':
				$this->loadImage();
				break;
		}
	}
	
	/**
	 * load image captcha
	 */
	
	public function loadImage() {
	
		$characters = strtoupper(randStr(5));
		
		$_SESSION['captcha'] = md5($characters);
		
		// create some random text positioning
		$cord_rand = rand(1,2);
		$from_left = rand(30,40);
		
		if($cord_rand == 1) {
			$twist = rand(5, 12);
			$from_top = rand(45,55); 
		} elseif($cord_rand == 2) {
			$twist = rand(-5, -15);
			$from_top = rand(30,35); 
		}
		
		// set font size
		$font_size = 30;
	
		// create image resource
		$image = imagecreatefrompng(System::get('modules') . 'Captcha/images/blank' . rand(1,6) . '.png');
		
		// set text colour to black
		$text_color = imagecolorallocate($image, 000,000,000);

		// add text to the image
		imagettftext($image, $font_size, $twist, $from_left, $from_top, $text_color, System::get('modules') . 'Captcha/fonts/trashco.ttf', $characters);

		// stop caching
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		
		// HTTP/1.0
		header("Pragma: no-cache"); 
		header('content-type: image/png');
		
		// send the image to the browser
		imagepng($image);
		
		// destroy the image
		imagedestroy($image);
			
	}

}
