<?php

/**
 * Time
 *
 * This class is used to retrieve DateTime objects  and to format dates into pretty text
 * 
 * @author Simon Jakowicz
 */

class Time {
    
	public static function now() {
		return  DateTime::createFromFormat('U', time());
	}
	
	public static function sub($time_to_subtract, $db_ready = null) {
		$datetime = Time::now();
		$datetime->modify('-30 min');
		
		if($db_ready) {
			 return $datetime->format('Y-m-d H:i:s');
		} else {
			return $datetime;
		}
	}
	
	public static function format($from_date) {
		
		// get the unix time of the date given -> will usually be from the database
		$from_unix = strtotime($from_date);
		
		// get the unix time of now
		$to_unix = time();
		
		// calculate the difference in time in seconds
		$seconds = $to_unix - $from_unix;
		
		if($seconds  < 60) {
			return 'Under a minute ago';
		} else if($seconds  < 300) {
			return 'The last 5 minutes';
		} else if($seconds  < 1800) {
			return 'In the last half hour';
		}  else if($seconds  < 3600) {
			return 'In the last hour';
		}  else if($seconds  < 10800) {
			return 'In the last 3 hours';
		}  else if($seconds  < 21600) {
			return 'In the last 6 hours';
		}  else if($seconds  < 43200) {
			return 'In the last 12 hours';
		}  else if($seconds  < 86400) {
			return 'In the last 24 hours';
		}  else if($seconds  < 172800) {
			return 'In the last 2 days';
		}  else if($seconds  < 432000) {
			return 'In the last 5 days';
		}  else if($seconds  < 604800) {
			return 'In the last week';
		}  else if($seconds  < 1209600) {
			return 'In the last 2 weeks';
		}  else if($seconds  < 1814400) {
			return 'In the last 3 weeks';
		}  else if($seconds  < 2678400) {
			return 'In the last month';
		}  else if($seconds  < 8035200) {
			return 'In the last 3 months';
		}  else if($seconds  < 24105600) {
			return 'In the last 6 months';
		}  else if($seconds  < 48211220) {
			return 'In the last year';
		}  else if($seconds  < 96422400) {
			return 'In the last 2 years';
		}  else if($seconds  > 96422400) {
			return 'Over 2 years ago';
		}

	}
	
	
	

}