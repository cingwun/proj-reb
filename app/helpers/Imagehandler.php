<?php

class Imagehandler{

	public static function url($content) {
		return basename($content);
	}

	public static function json($content) {
		$img=array();

		$cmd = json_decode($content);
		if(!empty($cmd)) {
			foreach ($cmd as $row) {
				$img[] = $row->id;
			}
		}

		return $img;
	}

	public static function html($content) {
		$html_content = "";

		$row = json_decode($content);
		if(is_null($row)) //check JSON format
			$html_content = $content;
		else
			$html_content = $row[0]->content;

		$html = strip_tags($html_content, '<img>');

		preg_match_all('/<img.*src="([^"]*)"/i', $html, $matches);
		$html = array();
		foreach($matches[1] as $row) {
			$html[] = basename($row);
		}
		return $html;
	}
}
?>
