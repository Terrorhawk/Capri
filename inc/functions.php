<?php

class template

	{
	function pagetit($pagetit, $tree)
		{
		global $sk;
		echo "<div class=\"pageTit\"><span>" . $pagetit . "</span>" . $tree . "</div>";
		}
	}

function getpath()
	{
	global $root;
	$skinpath = $root;
	if (preg_match("/\/home\\/(.+)\/skins\\/(.+)/", $skinpath))
		{
		$return["owner"] = "reseller";
		$return["path"] = $skinpath . "/inc/";
		}
	elseif (preg_match("/\\/usr\\/local\/directadmin\\/data\/skins\/(.+)/", $skinpath))
		{
		$return["owner"] = "admin";
		if (file_exists("/usr/local/directadmin/data/skin_data/capri"))
			{
			$return["path"] = "/usr/local/directadmin/data/skin_data/capri/";
			}
		  else
			{
			$return["path"] = $skinpath . "/inc/";
			}
		}
	  else
		{
		if (file_exists("/usr/local/directadmin/data/skin_data/capri"))
			{
			$return["owner"] = "admin";
			$return["path"] = "/usr/local/directadmin/data/skin_data/capri/";
			return $return;
			}
		  else
			{
			return false;
			}
		}

	return $return;
	}

function openfile($file)
	{
	if (file_exists($file))
		{
		if ($data = @file_get_contents($file))
			{
			return $data;
			}
		  else
			{
			return false;
			}
		}
	  else
		{
		return false;
		}
	}

function whitefile($str, $file)
	{
	if ($al = @fopen($file, "w"))
		{
		if (@is_writable($file))
			{
			@fwrite($al, $str);
			return true;
			}
		  else
			{
			return false;
			}

		@fclose($al);
		}
	  else
		{
		return false;
		}
	}

function saveLicense($licArr)
	{
	return;
	
	}

function readLicense()
	{
	return;
	}

function saveKey($key)
	{
	return;
	
	}

function getKey()
	{
	return "key";
	}

class skclass

	{
	function api_get($cmd, $post = false)
		{
		
		global $disable_api_on_ssl;
		if (is_array($post))
			{
			$is_post = true;
			$str = "";
			foreach($post as $var => $value)
				{
				if (strlen($str) > 0) $str.= "&";
				$str.= $var . "=" . urlencode($value);
				}

			$post = $str;
			}
		  else
			{
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
		if ($is_post)
			{
			$headers["Content-type"] = "application/x-www-form-urlencoded";
			$headers["Content-length"] = strlen($post);
			}

		$send = ($is_post ? "POST " : "GET ") . $cmd . " HTTP/1.1\r\n";
		foreach($headers as $var => $value) $send.= $var . ": " . $value . "\r\n";
		$send.= "\r\n";
		if ($is_post && strlen($post) > 0) $send.= $post . "\r\n\r\n";
		if ($SSL == 1)
			{
			$sIP = "ssl://127.0.0.1";
			}
		  else
			{
			$sIP = "127.0.0.1";
			}

		// connect
    $res = @fsockopen($sIP, '2222', $sock_errno, $sock_errstr, 1);

      if($sock_errno || $sock_errstr)
      {
      return false;
      }
      // send query
      @fputs($res, $send, strlen($send));
      // get reply
          
          
          $result = '';
      while(!feof($res))
      {
      $result .= fgets($res, 32768);
      }
      @fclose($res);
      // remove header
      $data = explode("\r\n\r\n", $result, 2);
          
          if(count($data) == 2)
      {
      return $data[1];
      }
      return false;
 }



	function iconmenu($title, $items)
		{
		$output = "";
		for ($i = 0; $i < count($items); $i++)
			{
			if ($items[$i]["plugin"])
				{
				$itemimg = "/IMG_IC_PLUGIN";
				$plugintxt = $items[$i]["plugin"];
				if (stristr($plugintxt, "stat")) $itemimg = "/IMG_IC_STATS";
				if (stristr($plugintxt, "awstats")) $itemimg = "/IMG_IC_AWSTATS";
				if (stristr($plugintxt, "smtp")) $itemimg = "/IMG_IC_STATS";
				if (stristr($plugintxt, "ruby")) $itemimg = "/IMG_IC_RUBY";
				if (stristr($plugintxt, "rails")) $itemimg = "/IMG_IC_RUBY";
				if (stristr($plugintxt, "smtp")) $itemimg = "/IMG_IC_SMTP_CONTROL";
				if (stristr($plugintxt, "billing")) $itemimg = "/IMG_IC_BILLING";
				if (stristr($plugintxt, "bill")) $itemimg = "/IMG_IC_BILLING";
				if (stristr($plugintxt, "payment")) $itemimg = "/IMG_IC_BILLING";
				if (stristr($plugintxt, "hotlink")) $itemimg = "/IMG_IC_HOTLINK";
				if (stristr($plugintxt, "itron")) $itemimg = "/IMG_IC_ITRON";
				if (stristr($plugintxt, "installatron")) $itemimg = "/IMG_IC_ITRON";
				if (stristr($plugintxt, "tomcat")) $itemimg = "/IMG_IC_TOMCAT";
				if (stristr(strtolower($plugintxt) , "pear")) $itemimg = "/IMG_IC_PEAR";
				if (stristr(strtolower($plugintxt) , "pgsql")) $itemimg = "/IMG_IC_PGSQL";
				if (stristr($plugintxt, "PostgreSQL")) $itemimg = "/IMG_IC_PGSQL";
				if (stristr($plugintxt, "Postgre")) $itemimg = "/IMG_IC_PGSQL";
				if (stristr(strtolower($plugintxt) , "softaculous")) $itemimg = "/IMG_IC_SOFTAC";
				if (stristr(strtolower($plugintxt) , "phpvs")) $itemimg = "/IMG_IC_PHPVS";
				if (stristr(strtolower($plugintxt) , "php version selector")) $itemimg = "/IMG_IC_PHPVS";
				$pluglink = preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>(.*?)<\/a>/", "<a href=\"\\2\"><img src=\"$itemimg\"><br />\\4</a>", $items[$i]["plugin"]);
				$output.= $pluglink;
				}
			  else
				{
				$output.= "      <a href=\"" . $items[$i]["link"] . "\"";
				if ($items[$i]["js"]) $output.= " onClick=\"" . $items[$i]["js"] . "\"";
				$output.= "><img src=\"" . $items[$i]["img"] . "\"><br />" . $items[$i]["txt"] . "</a>" . "\n";
				}
			}

		$start_menu = "    <fieldset class=\"buttons-box\"><legend>" . $title . "</legend>" . "\n";
		$end_menu = "    </fieldset>" . "\n";
		echo $start_menu . $output . $end_menu;
		}

	function submenu($title, $items, $footer = false)
		{
		$output = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><tr>";
		$div = 1;
		for ($i = 0; $i < count($items); $i++)
			{
			$output.= "<td width=\"20%\" align=\"center\"><a href=\"" . $items[$i]["link"] . "\" class=\"subitem\" " . $items[$i]["js"] . "><img src=\"" . $items[$i]["img"] . "\" width=\"32\" height=\"32\" border=\"0\"><br />" . $items[$i]["txt"] . "</a></td>";
			if ($div == 5)
				{
				$output.= "</tr><tr>";
				$div = 0;
				}

			$div++;
			}

		$output.= "</tr></table>";
		$start_menu = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=list><tr><td class=listtitle height=\"22\" style=\"padding-left:3px;
\"><b>" . $title . "</b></td></tr><tr><td class=list>";
		$end_menu = "</td></tr>";
		if ($footer) $end_menu.= "<tr><td class=\"listend\">" . $footer . "</td></tr>";
		$end_menu.= "</table>";
		echo $start_menu . $output . $end_menu;
		}

	function uptime($color = false)
		{
		$loads = urldecode($this->api_get("/CMD_API_LOAD_AVERAGE"));
		parse_str($loads);
		settype($one, "float");
		settype($five, "float");
		settype($fifteen, "float");
		$load = number_format($one, 2, ".", "") . ", " . number_format($five, 2, ".", "") . ", " . number_format($fifteen, 2, ".", "");
		return $load;
		}

	function getServices()
		{
		$str = $this->api_get("/CMD_API_SHOW_SERVICES", $post = false);
		if (strpos($str, "httpd") === false)
			{
			return false;
			}

		parse_str(urldecode($str) , $servArr);
		return $servArr;
		}

	function getLogo($creator, $username)
		{
		$logo = "/IMG_SKIN_HEADER";
		$arrPath = getpath();
		if (file_exists($arrPath["path"] . "logos/" . $creator))
			{
			$logo = "/IMG_RESLOGO_" . $creator;
			}

		if (file_exists($arrPath["path"] . "logos/" . $username))
			{
			$logo = "/IMG_RESLOGO_" . $username;
			}

		return $logo;
		}
	}

class AzDGCrypt

	{
	var $k;
	function AzDGCrypt($m)
		{
		$this->k = $m;
		}

	function ed($t)
		{
		$r = md5($this->k);
		$c = 0;
		$v = "";
		for ($i = 0; $i < strlen($t); $i++)
			{
			if ($c == strlen($r)) $c = 0;
			$v.= substr($t, $i, 1) ^ substr($r, $c, 1);
			$c++;
			}

		return $v;
		}

	function encrypt($t)
		{
		srand((double)microtime() * 1000000);
		$r = md5(rand(0, 32000));
		$c = 0;
		$v = "";
		for ($i = 0; $i < strlen($t); $i++)
			{
			if ($c == strlen($r)) $c = 0;
			$v.= substr($r, $c, 1) . (substr($t, $i, 1) ^ substr($r, $c, 1));
			$c++;
			}

		return base64_encode($this->ed($v));
		}

	function decrypt($t)
		{
		$t = $this->ed(base64_decode($t));
		$v = "";
		for ($i = 0; $i < strlen($t); $i++)
			{
			$md5 = substr($t, $i, 1);
			$i++;
			$v.= (substr($t, $i, 1) ^ $md5);
			}

		return $v;
		}
	}

function getLanguages($root)
	{
	$langdir = $root . "lang/";
	$languages = array();
	if ($handle = opendir($langdir))
		{
		while (false !== ($file = readdir($handle)))
			{
			if ($file != "." && $file != ".." && is_dir($langdir . $file))
				{
				$languages[] = $file;
				}
			}

		closedir($handle);
		}

	return $languages;
	}

function getDomainsList()
	{
	global $sk;
	$ret = array();
	$r = $sk->api_get("/CMD_API_DOMAIN_OWNERS");
	$domainsOwn = @urldecode($r);
	@parse_str($domainsOwn, $domains);
	if (is_array($domains) && count($domains) > 0)
		{
		foreach($domains as $domain => $ouwner)
			{
			$ret[str_replace("_", ".", $domain) ] = $ouwner;
			}
		}

	return $ret;
	}

function checkLicence($license, $localKey = '')
	{ //b85ee429d2d7cf3061d0eec7514dbe6a
    return;
	}

function make_token()
	{
	return ;
	}

function get_key()
	{
	return;
	}

function parse_local_key()
	{
	return;
	}

function validate_local_key($array)
	{
	return;
	}

function phpaudit_exec_socket($http_host, $http_dir, $http_file, $querystring)
	{
	return;
	
	}

function server_addr()
	{
	return ($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : $_SERVER["LOCAL_ADDR"];
	}

function getLocalKey($license)
	{
	return;
	}

$root = $_ENV["DOCUMENT_ROOT"] . "/";
if ($_ENV["DOCUMENT_ROOT"])
	{
	$root = $_ENV["DOCUMENT_ROOT"] . "/";
	}
elseif ($_SERVER["DOCUMENT_ROOT"])
	{
	$root = $_SERVER["DOCUMENT_ROOT"] . "/";
	}
  else
	{
	$root = getenv("DOCUMENT_ROOT") . "/";
	}

define("ROOT", $root);
$sk = new skclass();
$tpl = new template();
$enc = new AzDGCrypt("akr");

if ($_ENV["SCRIPT_NAME"])
	{
	$skinscript = $_ENV["SCRIPT_NAME"];
	}
elseif ($_SERVER["REQUEST_URI"])
	{
	$skinscript = $_SERVER["REQUEST_URI"];
	}
  else
	{
	$skinscript = getenv("SCRIPT_NAME");
	}



?>