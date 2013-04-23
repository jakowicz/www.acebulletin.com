<?php

/**
 * User
 *
 * Users are people which are accessing the site (with or without an account)
 * 
 * @author Simon Jakowicz
 */


class User extends Base_Model {

	protected $id;
	protected $email;
	protected $password;
	
	/**************************************************************************************************
	/* Getters & Setters
	/****/
	
	public function getID() {
		return (int) $this->id;
	}
	
	public function setID($id) {
		$this->id = (int) $id;
	}
	
	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $this->hashPassword($password);
	}

	/**************************************************************************************************
	/* The Real Deal
	/****/
	
	/**
	 * hash password using sha1
	 */
	
	public function hashPassword($password) {
		return sha1($password);
	}


}
	
?>