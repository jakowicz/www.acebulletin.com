<?php

/**
 * BBCode
 *
 * Convert BBCode to HTML
 * 
 * @author Simon Jakowicz
 */

class Bbcode {

	/**
	 * convert smileys and bbcode into html
	 */

	public static function getHTML($location, $text) {
		$text = Bbcode::convertSmileys($location, $text);
		$text = Bbcode::convertBbcode($text);
		return $text;
	}

	/**
	 * convert smiley text into smiley images
	 */

	public static function convertSmileys($location, $text) {
		$smileys = array(
			"SMILEYCODE" => array(
				":devil:", ":)", ":D", ":(", ";)", ":o", ":holy:", ":@", ":$"
			),
			"HTMLCODE" => array(
				'<img src="' . $location . '/devil.png" alt="Devil" />', 
				'<img src="' . $location . '/smile.png" alt="Smile" />', 
				'<img src="' . $location . '/bigsmile.png" alt="Big Smile" />', 
				'<img src="' . $location . '/sad.png" alt="Sad" />', 
				'<img src="' . $location . '/wink.png" alt="Wink" />', 
				'<img src="' . $location . '/suprised.png" alt="Suprised" />', 
				'<img src="' . $location . '/holy.png" alt="Holy" />', 
				'<img src="' . $location . '/angry.png" alt="Angry" />', 
				'<img src="' . $location . '/money.png" alt="Money" />'
			)
		);
		return str_ireplace($smileys['SMILEYCODE'], $smileys['HTMLCODE'], $text);
	}

	/**
	 * convert bbcode tags to html
	 */

	public static function convertBbcode($text) {

		//-------------------------
		// replace all [ with [~ inside code blocks, to prevent bbcode conversion
		//-------------------------

		//$code_match	= "#\[code\](.*?)\[([^~])(.*?)\[\/code\]#";
		$code_from = "#\[code\](.*?)\[([^~])(.*?)\[\/code\]#";
		$code_to = "[code]\\1[~\\2\\3[/code]";

		//-------------------------
		// code block
		//-------------------------	

		while (preg_match($code_from, $text)) {
			$text = preg_replace($code_from, $code_to, $text);
		}

		//-------------------------
		// quote block
		//-------------------------	

		$quote_from	= "#\[quote=?(.*?)\](.*?)\[\/quote\]#s";
		$quote_to = '<div class="quote_msg"><div class="quote_top"><strong>Quote</strong> - <em>\\1</em></div>\\2</div>';
		$quote_final_from = "<strong>Quote</strong> - <em></em>";
		$quote_final_to	= "<strong>Quote</strong>";

		while (preg_match($quote_from, $text)) {
			$text = preg_replace($quote_from, $quote_to, $text);
		}

		$text = str_replace($quote_final_from, $quote_final_to, $text);

		//-------------------------
		// basic tags
		//-------------------------

		$bb_tags = array("b" => "strong", "i" => "i", "sub" => "sub", "sup" => "sup", "s" => "s", "u" => "u");

		foreach ($bb_tags as $bb_tag_key => $bb_tag_value) {
			$text = preg_replace("#\[$bb_tag_key\](.*?)\[\/$bb_tag_key\]#s","<$bb_tag_value>\\1</$bb_tag_value>",$text);
		}

		//-------------------------
		// alignment tags
		//-------------------------

		$left_from =  "#\[left\](.*?)\[\/left\]#s";
		$left_to = '<div style="text-align:left;">\\1</div>';

		$center_from = "#\[center\](.*?)\[\/center\]#s";
		$center_to = '<div style="text-align:center;">\\1</div>';

		$right_from	= "#\[right\](.*?)\[\/right\]#s";
		$right_to = '<div style="text-align:right;">\\1</div>';

		$text = preg_replace($left_from   , $left_to   , $text);
		$text = preg_replace($center_from , $center_to , $text);
		$text = preg_replace($right_from  , $right_to  , $text);

		//-------------------------
		// font tags
		//-------------------------

		$color_from = "#\[color=(.*?)\](.*?)\[\/color\]#s";
		$color_to = '<span style="color:\\1">\\2</span>';

		$face_from = "#\[face=(.*?)\](.*?)\[\/face\]#s";
		$face_to = '<span style="font-family:\\1">\\2</span>';

		$size_from = "#\[size=(.*?)\](.*?)\[\/size\]#s";
		$size_to = '<font size="\\1">\\2</font>';

		$text = preg_replace($color_from , $color_to , $text);
		$text = preg_replace($face_from  , $face_to  , $text);
		$text = preg_replace($size_from  , $size_to  , $text);

		//-------------------------
		// url and img tags
		//-------------------------	

		$img_from  = "#\[img\](.*?)\[\/img\]#s";
		$img_to = '<img src="\\1" alt="User Image" />';

		$url_from = "#\[url\](.+?)\[\/url\]#s";
		$url_to = '<a href="\\1">\\1</a>';

		$url2_from = "#\[url=(.+?)\](.+?)\[\/url\]#s";
		$url2_to = '<a href="\\1">\\2</a>';

		$text = preg_replace($img_from  , $img_to  , $text);
		$text = preg_replace($url_from  , $url_to  , $text);
		$text = preg_replace($url2_from , $url2_to , $text);

		//-------------------------
		// lists
		//-------------------------	

		$list_from = "#\[list\](.*?)\[item\](.*?)\[\/item\](.*?)\[\/list\]#s";
		$list_to = "[list]\\1<li>\\2</li>\\3[/list]";
		$list_final_from = "#\[list\](.*?)(<li>)(.*?)\[\/list\]#s";
		$list_final_to = "<ul>\\1\\2\\3</ul>";

		while (preg_match($list_from, $text)) {
			$text = preg_replace($list_from, $list_to, $text);
		}

		$text = preg_replace($list_final_from, $list_final_to, $text);

		//-------------------------
		// finish the code block by coverting back all the [~ to [ 
		// it is safe now because all bbcode has already been converted so bbcode inside the code block will remain as bbcode
		//-------------------------	

		$text = str_replace(array("[~", "[code]", "[/code]"), array("[", '<div class="code_msg"><div class="code_top"><strong>Code</strong></div>', "</div>"), $text);

		//-------------------------
		// break lines
		//-------------------------
		
		$text = nl2br($text);

		return $text;
	}

}
