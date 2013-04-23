<?php

class Session {

	public static function setErrors($errors = array()) {
		$_SESSION['errors'] = $errors;
	}
	
	public static function getErrors() {
		$return =  $_SESSION['errors'] ? $_SESSION['errors'] : array();
		Session::clearErrors();
		return $return;
	}
	
	public static function clearErrors() {
		$_SESSION['errors'] = array();
	}
	
	public static function setData($data = array()) {
		$_SESSION['data'] = $data;
	}
	
	public static function getData() {
		$return = is_array($_SESSION['data']) ? $_SESSION['data'] : array();
		Session::clearData();
		return $return;
	}
	
	public static function clearData() {
		$_SESSION['data'] = array();
	}
	
	public static function hasFlash($name) {
		return isset($_SESSION['flash'][$name]);
	}
	
	public static function setFlash($name, $message) {
		$_SESSION['flash'][$name] = $message;
	}
	
	public static function getFlash($name) {
		$return = $_SESSION['flash'][$name];
		Session::clearFlash($name);
		return $return;
	}
	
	public static function clearFlash($name) {
		unset($_SESSION['flash'][$name]);
	}

}