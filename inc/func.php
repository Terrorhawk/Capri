<?php //need for optimisation functions.php


class skclass{
	
	public function StartSession($sessionid) { 
		session_id($sessionid);
		session_start();
    }
    public function ShowSslTip(){
    	if ($_SESSION['SSL_IGNORE_WHEN_LOCAL'] == 0){
       return "<div class=\"tip\" id=\"tip\">SSL is enabled but ssl_ignore_when_local is not active<br>Please add \"ssl_ignore_when_local =  1\" to your directadmin.conf to improve skin loading speed.</div>";
       }
       else {
       return false;
		}	
    }

	public function init_connection(){
		if( isset( $_SERVER["SSL"] ) ) {
		    $SSL = $_SERVER["SSL"];
		} else {
		    $SSL = $_ENV["SSL"];
		}
		
		if( isset( $_SERVER["SERVER_PORT"] ) ) {
		    $SERVER_PORT = $_SERVER["SERVER_PORT"];
		} else {
		    $SERVER_PORT = $_ENV["SERVER_PORT"];
		}
		
		if ($SSL == 1){
			$sIP = "http://127.0.0.1:".$SERVER_PORT;
		}
		else {
			$_SESSION['SSL_IGNORE_WHEN_LOCAL'] = 1;
			return true;
		}
		$headers = array(
		"Accept: */*" ,
		"Connection: Close",
		"Cookie: session={$_SERVER['SESSION_ID']}; key={$_SERVER['SESSION_KEY']}",
		"Content-Type: application/x-www-form-urlencoded",
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
		curl_setopt($ch, CURLOPT_URL, $sIP.'/CMD_API_ADMIN_STATS');
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_exec($ch);
		$info = curl_getinfo($ch);
		if ($info['http_code'] !=200){$_SESSION['SSL_IGNORE_WHEN_LOCAL'] = 0 ;return false;}
		$_SESSION['SSL_IGNORE_WHEN_LOCAL'] = 1;
		return true;
		
		

	}


	private function getApi($cmd, $arrData = false) {
		// Check if we have Curl installed. else return
		if (!function_exists('curl_version')){return false;}
		
		// Check for ssl 
		if( isset( $_SERVER["SSL"] ) ) {
		    $SSL = $_SERVER["SSL"];
		} else {
		    $SSL = $_ENV["SSL"];
		}
		if( isset( $_SERVER["SERVER_PORT"] ) ) {
		    $SERVER_PORT = $_SERVER["SERVER_PORT"];
		} else {
		    $SERVER_PORT = $_ENV["SERVER_PORT"];
		}
		
		
		if ($SSL == 1 && $_SESSION['SSL_IGNORE_WHEN_LOCAL'] == 0){
			$sIP = "https://127.0.0.1:".$SERVER_PORT;
		}
		else {
			$sIP = "http://127.0.0.1:".$SERVER_PORT;
		}
		
		$url = $sIP.$cmd;
		// make the magic headers
		$headers = array(
		"Accept: */*" ,
		"Connection: Close",
		"Cookie: session={$_SERVER['SESSION_ID']}; key={$_SERVER['SESSION_KEY']}",
		"Content-Type: application/x-www-form-urlencoded",
		);
		// Making the post vars
		if ($arrData){
		$pairs = array();
		foreach ( $arrData as $key => $value )
		{
			$pairs[] = "$key=".urlencode($value);
		}
		$content = join('&',$pairs);
		unset($pairs);
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($arrData) {curl_setopt($ch, CURLOPT_POSTFIELDS, $content);}
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_ENCODING,  '');
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		$retxml = curl_exec($ch);
		if (curl_errno($ch)) {
		//	echo "<!--API CALL ERROR $cmd  -->";
			return false;
		}
		curl_close($ch);
		//echo "<!--API CALL $cmd  -->";
			return $retxml;
}

	public function getLoadAverage() {
		$load = sys_getloadavg();
		$load = number_format($load[0], 2, ".", "") . ", " . number_format($load[1], 2, ".", "") . ", " . number_format($load[2], 2, ".", "");
		return $load;
	}
	public function getServices() {
		if (!$str = $this->getApi("/CMD_API_SHOW_SERVICES", $post = false)){return false;}
		if (strpos($str, "httpd") === false){
			return false;
		}
		parse_str(urldecode($str) , $servArr);
		return $servArr;
	}
	public function getAllDomainsList() {
		$ret = array();
		if (!$r = $this->getApi("/CMD_API_DOMAIN_OWNERS")){return false;}
		$domainsOwn = urldecode($r);
		parse_str($domainsOwn, $domains);
		if (is_array($domains) && count($domains) > 0) {
			foreach($domains as $domain => $ouwner) {
				$ret[str_replace("_", ".", $domain) ] = $ouwner;
			}
		}
		return $ret;
	}
	public function getUserDomainsList() {
		if (!$r = $this->getApi("/CMD_API_SHOW_DOMAINS")){return false;}
		$domainsOwn = urldecode($r);
		parse_str($domainsOwn, $domains);
		return $domains;
	}
	public function getAdminStats() {
		if (!$r = $this->getApi("/CMD_API_ADMIN_STATS")){return false;}
		$stats = urldecode($r);
		parse_str($stats, $statsArr);
		return $statsArr;
	}
	public function getUserStats() {
		if (!$r = $this->getApi("/CMD_API_SHOW_USER_USAGE")){ return false;}
		$stats = urldecode($r);
		parse_str($stats, $statsArr);
		return $statsArr;
	}
	public function getMailQuota($domain) {
		$post = array('action'=>'list', 'type'=>'quota', 'domain'=>$domain);
		if (!$r = $this->getApi("/CMD_API_POP", $post)){ return false;}
		parse_str($r, $accounts);
		return $accounts;
	}
	public function changeLang($lang) {
		$post = array("language"=>1, "lvalue"=>$lang);
		if (!$r = $this->getApi('/CMD_API_CHANGE_INFO', $post)){return false;}
		parse_str($r, $resultArray);
		$output = $this->jsonEncode($resultArray);
		return $output;
	}
	private function jsonEncode($arr) {
	    $parts = array();
	    $is_list = false;
	    //Find out if the given array is a numerical array
	    $keys = array_keys($arr);
	    $max_length = count($arr)-1;
	    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
	        $is_list = true;
	        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
	            if($i != $keys[$i]) { //A key fails at position check.
	                $is_list = false; //It is an associative array.
	                break;
	            }
	        }
	    }
	    foreach($arr as $key=>$value) {
	        if(is_array($value)) { //Custom handling for arrays
	            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
	            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
	        } else {
	            $str = '';
	            if(!$is_list) $str = '"' . $key . '":';
	            //Custom handling for multiple data types
	            if(is_numeric($value)) $str .= $value; //Numbers
	            elseif($value === false) $str .= 'false'; //The booleans
	            elseif($value === true) $str .= 'true';
	            else $str .= '"' . addslashes($value) . '"'; //All other things
	            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
	            $parts[] = $str;
	        }
	    }
	    $json = implode(',',$parts);
	    
	    if($is_list) return '[' . $json . ']';//Return numerical JSON
	    return '{' . $json . '}';//Return associative JSON
	}
}
class logoclass{
	public function addCustomLogoConf($user, $logopath, $skroot) { 
		$content = ""; 
		$confpath = $skroot . "/files_custom.conf";
		$customLogoArr = parse_ini_file($confpath);
		$customLogoArr["IMG_RESLOGO_" . $user] = $logopath;
		foreach ($customLogoArr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key."[]=".$elem[$i]."\n"; 
                } 
            } 
            else if($elem=="") $content .= $key." = \n"; 
            else $content .= $key."=".$elem."\n"; 
        } 
	    if (!$handle = fopen($confpath, 'w')) { 
	        return false; 
	    }
	    $success = fwrite($handle, $content);
	    fclose($handle); 
	    return $success;
	}
	public function uploadLogoUrl($logourl, $user, $skroot) {
		$imgcheck = getimagesize ($logourl);
		list($w, $h, $t, $x) = $imgcheck;
	    if(($t==1  || $t==2 || $t==3) && $w<=300 && $h<=60) {
	        $extfile = image_type_to_extension($imgcheck[2]);
	        $logodata = file_get_contents($logourl);
	        $logopath = "images/custom/". $user . $extfile;
	        $fullLogoPath = $skroot . "/" . $logopath;
	        file_put_contents($fullLogoPath, $logodata);
	        if(!$this->addCustomLogoConf($user, $logopath, $skroot)) {
	          @unlink($fullLogoPath);
	          return 1;
	        } else {
	          return 0;
	        }
	    } else {
	        return 2;
	    }
	}
}
class fileclass{
	public function openfile($file) {
		if (file_exists($file)) {
			if ($data = @file_get_contents($file)) {
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function whitefile($str, $file) {
		if ($al = @fopen($file, "w")) {
			if (@is_writable($file)) {
				@fwrite($al, $str);
				return true;
			} else {
				return false;
			}
			@fclose($al);
		} else {
			return false;
		}
	}
}
$sk = new skclass();
$logo = new logoclass();
$fl = new fileclass();
?>