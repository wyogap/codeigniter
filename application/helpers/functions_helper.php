<?php
	

	/**
	 * get_hash_token
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_hash_token'))
	{
		function get_hash_token() {
			return md5(get_hash_number());
		}
	}

	/**
	 * get_hash_number
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_hash_number'))
	{
		function get_hash_number() {
			return time()*rand(1, 99999);
		}
	}

	/**
	 * get_system_protocol
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_system_protocol'))
	{
		function get_system_protocol() {
			$is_secure = false;
			if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
				$is_secure = true;
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
				$is_secure = true;
			}
			return $is_secure ? 'https' : 'http';
		}
	}

	/**
	 * get_ip
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_user_ip'))
	{
		function get_user_ip() {
			/* handle CloudFlare IP addresses */
			return (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);
		}
	}

	/**
	 * get_os
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_user_os'))
	{
		function get_user_os() {
			$os_platform = "Unknown OS Platform";
			$os_array = array(
				'/windows nt 10/i'      =>  'Windows 10',
				'/windows nt 6.3/i'     =>  'Windows 8.1',
				'/windows nt 6.2/i'     =>  'Windows 8',
				'/windows nt 6.1/i'     =>  'Windows 7',
				'/windows nt 6.0/i'     =>  'Windows Vista',
				'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'     =>  'Windows XP',
				'/windows xp/i'         =>  'Windows XP',
				'/windows nt 5.0/i'     =>  'Windows 2000',
				'/windows me/i'         =>  'Windows ME',
				'/win98/i'              =>  'Windows 98',
				'/win95/i'              =>  'Windows 95',
				'/win16/i'              =>  'Windows 3.11',
				'/macintosh|mac os x/i' =>  'Mac OS X',
				'/mac_powerpc/i'        =>  'Mac OS 9',
				'/linux/i'              =>  'Linux',
				'/ubuntu/i'             =>  'Ubuntu',
				'/iphone/i'             =>  'iPhone',
				'/ipod/i'               =>  'iPod',
				'/ipad/i'               =>  'iPad',
				'/android/i'            =>  'Android',
				'/blackberry/i'         =>  'BlackBerry',
				'/webos/i'              =>  'Mobile'
			);
			foreach($os_array as $regex => $value) {
				if(preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
					$os_platform = $value;
				}
			}   
			return $os_platform;
		}
	}

	/**
	 * get_browser
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_user_browser'))
	{
		function get_user_browser() {
			$browser = "Unknown Browser";
			$browser_array = array(
				'/msie/i'       =>  'Internet Explorer',
				'/firefox/i'    =>  'Firefox',
				'/safari/i'     =>  'Safari',
				'/chrome/i'     =>  'Chrome',
				'/edge/i'       =>  'Edge',
				'/opera/i'      =>  'Opera',
				'/netscape/i'   =>  'Netscape',
				'/maxthon/i'    =>  'Maxthon',
				'/konqueror/i'  =>  'Konqueror',
				'/mobile/i'     =>  'Handheld Browser'
			);
			foreach($browser_array as $regex => $value) {
				if(preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
					$browser = $value;
				}
			}
			return $browser;
		}
	}

	/**
	 * secure
	 * 
	 * @param string $value
	 * @param string $type
	 * @param boolean $quoted
	 * @return string
	 */
	if ( ! function_exists('secure'))
	{
		function secure($value, $type = "", $quoted = true) {
			global $_db;
			if($value !== 'null') {
				// [1] Sanitize
				/* Convert all applicable characters to HTML entities */
				$value = htmlentities($value, ENT_QUOTES, 'utf-8');
				// [2] Safe SQL
				$value = $_db->escape($value);
			}
			return $value;
		}
	}
	
	/**
	 * nohtml
	 * 
	 * @param string $message
	 * @return string
	 */
	if ( ! function_exists('nohtml'))
	{
		function nohtml($message) 
		{
			if (!isset($message) || $message == null || $message == "")
				return $message;

			$message = trim($message);
			$message = strip_tags($message);
			$message = htmlspecialchars($message, ENT_QUOTES);
			return $message;
		}
	}

	if ( ! function_exists('crypto_rand_secure'))
	{
		function crypto_rand_secure($min, $max)
		{
			$range = $max - $min;
			if ($range < 1) return $min; // not so random...
			$log = ceil(log($range, 2));
			$bytes = (int) ($log / 8) + 1; // length in bytes
			$bits = (int) $log + 1; // length in bits
			$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
			do {
				$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
				$rnd = $rnd & $filter; // discard irrelevant bits
			} while ($rnd > $range);
			return $min + $rnd;
		}
	}

	if ( ! function_exists('generate_token'))
	{
		function generate_token($length)
		{
			$token = "";
			$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
			$codeAlphabet.= "0123456789";
			$max = strlen($codeAlphabet); // edited

			for ($i=0; $i < $length; $i++) {
				$token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
			}

			return $token;
		}
	}

	if ( ! function_exists('utc_to_localtime'))
	{
		function utc_to_localtime($utc = '', $format = 'Y-m-d H:i:s') {
			$dt = get_localtime($utc);

			//store as number
			return $dt->format($format);
		}
	}

	if ( ! function_exists('timestamp_to_localtime'))
	{
		function timestamp_to_localtime($timestamp = 0) {
			$dt = get_localtime($timestamp);

			//as timestamp
			return $dt->getTimestamp();
		}
	}

	if ( ! function_exists('get_localtime'))
	{
		function get_localtime($utc = 0) {
			if (empty($utc))
				$dt = new DateTime(date("Y-m-d H:i:s"), new DateTimeZone('UTC')); 		//server time is in UTC
			else if (is_numeric($utc))
				$dt = new DateTime(date("Y-m-d H:i:s", $utc), new DateTimeZone('UTC')); //server time is in UTC
			else
				$dt = new DateTime($utc, new DateTimeZone('UTC')); //server time is in UTC

			//convert to local time
			$ci	=&	get_instance();
			if (!empty($ci->session->userdata('time_zone'))) {
				$tz = $ci->session->userdata('time_zone');
			}
			else {
				$tz = config_item('app_time_zone');
				$ci->session->set_userdata('time_zone', $tz);
			}

			$dt->setTimezone(new DateTimeZone($tz));

			//store as number
			return $dt;
		}
	}

	if ( ! function_exists('get_localtime_string'))
	{
		function get_localtime_string($format = 'Y-m-d H:i:s', $timestamp = 0) {
			$dt = get_localtime($timestamp);

			//format
			return $dt->format($format);
		}
	}

	if ( ! function_exists('array_insert'))
	{
		function array_insert($array, $index, $val)
		{
			$size = count($array); //because I am going to use this more than one time
			if (!is_int($index) || $index < 0 || $index > $size)
			{
				return -1;
			}
			else
			{
				$temp   = array_slice($array, 0, $index);
				$temp[] = $val;
				return array_merge($temp, array_slice($array, $index, $size));
			}
		}
	}
?>