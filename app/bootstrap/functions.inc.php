<?php

/**
 * generate random string
 *
 * @author Simon Jakowicz
 * @param int $length
 * @return string
 */

function randStr($length) {
	$alphanum = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijzlmnopqrstuvwcyz0123456789";
	$rand = substr(str_shuffle($alphanum), 0, $length);
	return $rand;
}

/**
 * return the word even or odd based on the number given
 *
 * @author Simon Jakowicz
 * @param int $number
 * @return string
 */

function oddOrEven($number) {
	return $number % 2 == 0 ? 'even' : 'odd';
}

/**
 * get the text in between two other blocks
 *
 * @author Simon Jakowicz
 * @param string $block
 * @param string $start_text
 * @param string $end_text
 * @return string
 */

function getMiddleText($block, $start_text, $end_text) {
	$start_pos = strpos($block, $start_text) + strlen($start_text);
	$end_pos   = strpos($block, $end_text, $start_pos);
	$length    = $end_pos - $start_pos;
	return substr($block, $start_pos, $length);
	
}