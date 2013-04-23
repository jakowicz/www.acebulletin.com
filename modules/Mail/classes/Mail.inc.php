<?php

/**
 * Mail Templates
 *
 * Simple email template setup
 * 
 * @author Simon Jakowicz
 */

class Mail {

	public static function getTemplate($variables = array(), $view = null, $component = null) {
		
		ob_start();
		Templater::render($variables, $view, $component);
		$body = ob_get_contents();
		ob_end_clean();

		return eregi_replace("[\]", '', $body);
	}

	public $body;

	/**
	 * load the template contents
	 */
	 
	public function __construct($variables = array(), $view = null, $component = null) {
		$this->body = $this->getTemplate($variables, $view, $component);
	}
	
	/**
	 * send mail
	 */
	
	function send($subject, $to_name = null, $to_email = null) {
		
		// load php mailer if it doesnt exist already
		if(!class_exists("PHPMailer")) {
			require_once(System::get('root') . 'app/lib/php_mailer/class.phpmailer.php');
		}
		
		// send mail
		$mailer = new PHPMailer();
		$mailer->SetFrom(System::get('admin_email'), System::get('site_name'));
		$mailer->AddAddress($to_email, $to_name);
		$mailer->Subject = System::get('site_name') . ' - ' . $subject;
		$mailer->MsgHTML($this->body);
		$mailer->Send();
	}
		
}