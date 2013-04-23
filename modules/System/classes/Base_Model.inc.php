<?php

class Base_Model {

	public function toArray($remote_attributes = array()) {
		
		$data = get_object_vars($this);
		
		// remove attributes that we don't want
		foreach ($remote_attributes as $remote_attribute) {
			unset($data[$remote_attribute]);
		}
		
		return $data;
	}

}