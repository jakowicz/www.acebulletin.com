<?php

class Ini {

    const SINGLE_QUOTE = '<<<-SGL_QUOTE->>>';
    const DOUBLE_QUOTE = '<<<-DBL_QUOTE->>>';

    public static function replace($contents, $find, $replace) {


        // new contents
        $new_contents = preg_replace('/' . $find . '/', $replace, $contents);

        return $new_contents;

    }

    public static function escape($contents) {
    
        $contents = str_replace(array("'", '"'), array(self::SINGLE_QUOTE, self::DOUBLE_QUOTE), $contents);

        return $contents;

    }

    public static function unescape($contents) {
    
        $contents = str_replace(array(self::SINGLE_QUOTE, self::DOUBLE_QUOTE), array("'", '"'), $contents);
        return $contents;

    }

}