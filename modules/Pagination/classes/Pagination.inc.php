<?php

/**
 * Pagination
 * 
 * @author Simon Jakowicz
 */

class Pagination {
	
	private $page;
	private $per_page;
	private $start_record;
	private $total_records;
	private $max_pages;

	/**
	 * get start record
	 */

	public  function setStartRecord($page, $show_per_page) {
	
		// set amount to show per page
		$this->per_page = $show_per_page;
		
		// if $page is note set or is not numeric or less than 1, reset to 1
		if(empty($page) || !is_numeric($page) || $page < 1)  {
			$this->page = 1;
		} else {
			// type cast as is_numeric() will allow floating points
			$this->page = (int) $page;
		}
		
		// reset current page if it's greater then the max pages value
		if($page > $this->getMaxPages()) {
			$this->page = $this->getMaxPages();
		}
		
		// set the start record -> zero based
		$this->start_record = ($this->page * $show_per_page) - $show_per_page;
			
	}
	
	/**
	 * create a limit clause for a query
	 */

	public  function createLimitClause() {
	
		return  $this->start_record . ', ' . $this->per_page;
			
	}
	
	/**
	 * set total number of records in all pages
	 */
	
	public function setTotalRecords($total) {
		$this->total_records = $total;
	
	}
		
	/**
	 * get max pages
	 */
	
	public function getMaxPages() {
		if(!$this->max_pages) {
			// calculate max pages and round up
			$max_pages = $this->total_records / $this->per_page;
			$this->max_pages = ceil($max_pages);
		}
		return $this->max_pages;
	}
	
	/**
	 * get page
	 */
	
	public function getPage() {
		return $this->page;
	}

}