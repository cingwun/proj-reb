<?php

class Urlhandler {

	public static function encode_url($string) {
	    //$entities = array('%20', '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
	    $entities = array(' ', '!', '*', "'", "(", ")", ";", ":", "@", "&#038;", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
	    $replacements = '+';
	    return str_replace($entities, $replacements, $string);
	}
}

?>
