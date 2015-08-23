<?php
//need for optimisation functions.php

class skclass{
	function api_get($cmd, $post = false) {
		
		global $disable_api_on_ssl;
		if (is_array($post)) {
			$is_post = true;
			$str = "";
			foreach($post as $var => $value) {
				if (strlen($str) > 0) $str.= "&";
				$str.= $var . "=" . urlencode($value);
			}

			$post = $str;
		}
		else {
			$is_post = false;
		}

		$_SERVER_PORT = $_ENV["SERVER_PORT"];
		if (!$_ENV["SERVER_PORT"] && $_SERVER["SERVER_PORT"]) $_SERVER_PORT = $_SERVER["SERVER_PORT"];
		$_SESSION_KEY = $_ENV["SESSION_KEY"];
		if (!$_ENV["SESSION_KEY"] && $_SERVER["SESSION_KEY"]) $_SESSION_KEY = $_SERVER["SESSION_KEY"];
		$_SESSION_ID = $_ENV["SESSION_ID"];
		if (!$_ENV["SESSION_ID"] && $_SERVER["SESSION_ID"]) $_SESSION_ID = $_SERVER["SESSION_ID"];
		$SSL = $_ENV["SSL"];
		if (!$_ENV["SSL"] && $_SERVER["SSL"]) $SSL = $_SERVER["SSL"];
		if ($disable_api_on_ssl == 1) return false;
		
		$headers = array();
		$headers["Host"] = "127.0.0.1:" . $_SERVER_PORT;
		$headers["Cookie"] = "session=" . $_SESSION_ID . "; key=" . $_SESSION_KEY;
		if ($is_post) {
			$headers["Content-type"] = "application/x-www-form-urlencoded";
			$headers["Content-length"] = strlen($post);
		}

		$send = ($is_post ? "POST " : "GET ") . $cmd . " HTTP/1.1\r\n";
		foreach($headers as $var => $value) $send.= $var . ": " . $value . "\r\n";
		$send.= "\r\n";
		if ($is_post && strlen($post) > 0) $send.= $post . "\r\n\r\n";
		if ($SSL == 1){
			$sIP = "ssl://127.0.0.1";
		}
		else {
			$sIP = "127.0.0.1";
		}

		// connect
		$res = @fsockopen($sIP, '2222', $sock_errno, $sock_errstr, 1);
		if($sock_errno || $sock_errstr) {
			return false;
		}
		// send query
		@fputs($res, $send, strlen($send));
		// get reply

		$result = '';
		while(!feof($res)) {
			$result .= fgets($res, 32768);
		}
		@fclose($res);
		// remove header
		$data = explode("\r\n\r\n", $result, 2);

		if(count($data) == 2) {
			return $data[1];
		}

		return false;
	}

	function getServices() {
		$str = $this->api_get("/CMD_API_SHOW_SERVICES", $post = false);
		if (strpos($str, "httpd") === false){
			return false;
		}

		parse_str(urldecode($str) , $servArr);
		return $servArr;
	}
}

$sk = new skclass();


?>