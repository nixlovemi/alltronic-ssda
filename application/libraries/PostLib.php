<?php
class PostLib {
	public static function getPost() {
		$postdata = file_get_contents("php://input");

		if($postdata != "" && !is_array($postdata)){
			parse_str($postdata, $jsonVars);
		} else {
			$jsonStr  = json_encode($_REQUEST);
			$jsonVars = json_decode($jsonStr);
		}

		return $jsonVars;
	}
}
