<?php

/**
 * Uploader
 *
 * File Uploader
 * 
 * @author Simon Jakowicz
 */

class Uploader {

	private $file;
	private $max_width;
	private $max_height;
	private $extension;
	private $save_path;
	private $save_location;
	private $max_bytes;
	private $extensions;

	/**
	 * set up attributes for performing validation on the file uploaded
	 */

	public function __construct($file, $save_location, $max_bytes = null, $extensions = null, $max_height = null, $max_width = null) {
		
		// set class attributes
		$this->setFile($file);
		$this->save_path = $save_location;
		$this->save_location = System::get('root') . $this->save_path . '.' . $this->extension;
		
		// set max dimensions
		if($max_height && $max_width) {
			$this->max_width = $max_width;
			$this->max_height = $max_height;
		}
		
		// set max size
		if(!empty($max_bytes)) {
			$this->max_bytes = $max_bytes;
		}
		
		// set allowed file types
		if(!empty($extensions)) {
			$this->setAllowedExtensions($extensions);
		}
		
	}
	
	/**
	 * get extension
	 */
	 
	public function getExtension() {
		return $this->extension;
	}
	
	/**
	 * set file and extension
	 */
	 
	public function setFile($file) {		
		// set file
		$this->file = $file;
		
		// set extension
		$file_parts = explode('.', $this->file['name']);
		$this->extension = strtolower(end($file_parts));

	}
	
	/**
	 * get error code
	 */
	 
	public function getErrorCode() {		
		return $this->file['error'];
	}
	
	/**
	 * set allowed extensions
	 */
	 
	public function setAllowedExtensions($extensions) {		
		$this->extensions = explode(',', $extensions);
	}
	
	/**
	 * check uplaod was successful
	 */
	
	public function validUpload() {	
		if($this->file['error'] == 0) {
			return true;
		}
		return false;
	}
	
	/**
	 * check file type is allowed
	 */
	
	public function validExtension() {	
		if(in_array($this->extension, $this->extensions)) {
			return true;
		}
		return false;
	}
	
	
	/**
	 * check file size is ok
	 */
	
	public function validSize() {
		if($this->file['size'] <= $this->max_bytes) {
			return true;
		}
		return false;
	}
	
	/**
	 * check image dimensions are ok
	 */

	public function validDimensions() {
		list($width, $height) = getimagesize($this->file['tmp_name']);
	
		if($height <= $this->max_height && $width <= $this->max_width) {
			return true;
		}
		return false;					
	}
	
		/**
	 * perform upload
	 */
	 
	public function upload() {
		if(move_uploaded_file($this->file['tmp_name'], $this->save_location)) { 
			return true;
		}			
		return false;
	}
	

}