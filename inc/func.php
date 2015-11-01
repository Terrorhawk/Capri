<?php //need for optimisation functions.php

class skclass{
	private function getApi($cmd, $post = false) {
		//var_dump($_SERVER);
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
		if( isset( $_SERVER["SERVER_PORT"] ) ) {
		    $_SERVER_PORT = $_SERVER["SERVER_PORT"];
		} else {
		    $_SERVER_PORT = $_ENV["SERVER_PORT"];
		}

		if( isset( $_SERVER["SESSION_KEY"] ) ) {
		    $_SESSION_KEY = $_SERVER["SESSION_KEY"];
		} else {
		    $_SESSION_KEY = $_ENV["SESSION_KEY"];
		}    

		if( isset( $_SERVER["SESSION_ID"] ) ) {
		    $_SESSION_ID = $_SERVER["SESSION_ID"];
		} else {
		    $_SESSION_ID = $_ENV["SESSION_ID"];
		}
		
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

                $sIP = "127.0.0.1";

		// connect
		$res = @fsockopen($sIP, $_SERVER_PORT, $sock_errno, $sock_errstr, 5);
		if($sock_errno || $sock_errstr) {
			return false;
		}
		if ($res) {
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
                     } else {
                        return false;
                     }

        } else {
        	return false;
        }
 
	}
        
    public function checkLocalSsl() {
    	if(( $_SERVER["SSL"] == 1 ) && (strstr($this->getApi("/CMD_API_LOAD_AVERAGE"), "use https"))) {
		    return true;
		} else {
		    return false; 
		}
	}

	public function getLoadAverage() {
		$load = sys_getloadavg();
		$load = number_format($load[0], 2, ".", "") . ", " . number_format($load[1], 2, ".", "") . ", " . number_format($load[2], 2, ".", "");
		return $load;
	}

	public function getServices() {
		if (!$str = $this->getApi("/CMD_API_SHOW_SERVICES")){return false;}
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
		if (!$r = $this->getApi("/CMD_API_SHOW_USER_USAGE")){return false;}
		$stats = urldecode($r);
		parse_str($stats, $statsArr);
		return $statsArr;
	}

	public function getMailQuota($domain) {
		$post = array('action'=>'list', 'type'=>'quota', 'domain'=>$domain);
		if (!$r = $this->getApi("/CMD_API_POP", $post)){return false;}
		$res = urldecode($r);
		parse_str($res, $accounts);
		return $accounts;
	}

	public function changeLang($lang) {
		$post = array("language"=>1, "lvalue"=>$lang);
		if (!$r = $this->getApi('/CMD_API_CHANGE_INFO', $post)){return false;}
		parse_str($r, $resultArray);
  		$output = json_encode($resultArray);
		return $output;
	}

	// is deprecaded and be removed after tests (now use native json_encode)
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
