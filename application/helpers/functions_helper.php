<?php
	
	global $_config, $_db;

	$_config = array();

	$_config['cookie_user_id'] = "c_user";
	$_config['cookie_user_token'] = "xs";
	$_config['cookie_user_referrer'] = "ref";

	$_config['brute_force_detection_enabled'] = 1;

	$ci =& get_instance();
	isset($ci->db) OR $ci->load->database();	
	$_db = $ci->db;

	if ( ! function_exists('get_version'))
	{
		function get_version() {
			return "1.0";
		}
	}
	
	if (!function_exists('str_truncate')) {
		function str_truncate($in, $len) {
			if ($in == null)	return $in;
			return strlen($in) > $len ? substr($in,0,$len)."..." : $in;
		}
	}

	if (!function_exists('str_starts_with')) {
		function str_starts_with($haystack, $needle, $case = true)
		{
		  if ($case) {
			return strpos($haystack, $needle, 0) === 0;
		  }
		  return stripos($haystack, $needle, 0) === 0;
		}
	}
	  
	if (!function_exists('str_ends_with')) {
		function str_ends_with($haystack, $needle, $case = true)
		{
		  $expectedPosition = strlen($haystack) - strlen($needle);
		  if ($case) {
			return strrpos($haystack, $needle, 0) === $expectedPosition;
		  }
		  return strripos($haystack, $needle, 0) === $expectedPosition;
		}
	}

	if ( ! function_exists('str_replace_first'))
	{
		function str_replace_first($needle, $replace, $haystack) {
			$pos = strpos($haystack, $needle);
			if ($pos !== false) {
				return substr_replace($haystack, $replace, $pos, strlen($needle));
			}

			return $haystack;
		}
	}

	if ( ! function_exists('str_replace_last'))
	{
		function str_replace_last($needle, $replace, $haystack) {
			$pos = strrpos($haystack, $needle);
			if ($pos !== false) {
				return substr_replace($haystack, $replace, $pos, strlen($needle));
			}

			return $haystack;
		}
	}

	if ( ! function_exists('get_upload_list'))
	{	
		function get_upload_list($values) {
			global $_db;
			
			$sql = "
			select 
				group_concat(x.id separator ',') as upload_id,
				group_concat(x.filename separator ';') as filename,
				group_concat(x.web_path separator ';') as web_path,
				group_concat(x.thumbnail_path separator ';') as thumbnail_path
			from dbo_uploads x 
			where x.is_deleted=0 and find_in_set(x.id, ?) > 0
			";

			return $_db->query($sql, array($values))->row_array();
		}
	}


	if ( ! function_exists('get_upload_info'))
	{	
		function get_upload_info($file_id) {
			global $_db;
			
			$sql = "
			select 
				x.id as upload_id,
				x.filename,
				x.web_path,
				x.thumbnail_path
			from dbo_uploads x 
			where x.is_deleted=0 and x.id=?
			";

			return $_db->query($sql, array($file_id))->row_array();
		}
	}	
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

	// function connect_mysql() {
	// 	global $db;
	// 	// connect to the database
	// 	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
	// 	$db->set_charset('utf8mb4');
	// 	if(mysqli_connect_error()) {
	// 		_error(DB_ERROR);
	// 	}
	// 	/* set db time to UTC */
	// 	$db->query("SET time_zone = '+0:00'");
	// }

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
		function secure_legacy($value, $type = "", $quoted = true) {
			/**
			 * @var object
			 */
			global $_db;

			if($value !== 'null') {
				// [1] Sanitize
				/* Convert all applicable characters to HTML entities */
				$value = htmlentities($value, ENT_QUOTES, 'utf-8');
				// [2] Safe SQL
				$value = $_db->real_escape_string($value);
				switch ($type) {
					case 'int':
						$value = ($quoted)? "'".intval($value)."'" : intval($value);
						break;
					case 'datetime':
						$value = ($quoted)? "'".set_datetime($value)."'" : set_datetime($value);
						break;
					case 'search':
						if($quoted) {
							$value = (!is_empty($value))? "'%".$value."%'" : "''";
						} else {
							$value = (!is_empty($value))? "'%%".$value."%%'" : "''";
						}
						break;
					default:
						$value = (!is_empty($value))? "'".$value."'" : "''";
						break;
				}
			}
			return $value;
		}
	}

	/* ------------------------------- */
	/* Date */
	/* ------------------------------- */

	/**
	 * set_datetime
	 * 
	 * @param string $date
	 * @return string
	 */
	function set_datetime($date) {
		global $system;
		$date = str_replace(['??', '??', '??', '??', '??', '??', '??', '??', '??','??'], range(0, 9), $date); /* check and replace arabic numbers if any */
		$datetime = DateTime::createFromFormat($system['system_datetime_format'], $date);
		return $datetime->format("Y-m-d H:i:s");
	}


	/**
	 * get_datetime
	 * 
	 * @param string $date
	 * @return string
	 */
	function get_datetime($date) {
		global $system;
		return date($system['system_datetime_format'], strtotime($date));
	}


	/* ------------------------------- */
	/* STRING */
	/* ------------------------------- */

	/**
	 * is_empty
	 * 
	 * @param string $value
	 * @return boolean
	 */
	function is_empty($value) {
		if(strlen(trim(preg_replace('/\xc2\xa0/',' ',$value))) == 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * valid_email
	 * 
	 * @param string $email
	 * @return boolean
	 */
	function valid_email($email) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * valid_url
	 * 
	 * @param string $url
	 * @return boolean
	 */
	function valid_url($url) {
		if(filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * valid_username
	 * 
	 * @param string $username
	 * @return boolean
	 */
	function valid_username($username) {
		if(strlen($username) >= 3 && preg_match('/^[a-zA-Z0-9]+([_|.]?[a-zA-Z0-9])*$/', $username)) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * reserved_username
	 * 
	 * @param string $username
	 * @return boolean
	 */
	function reserved_username($username) {
		$reserved_usernames = array('install', 'static', 'contact', 'contacts', 'sign', 'signin', 'login', 'signup', 'register', 'signout', 'logout', 'reset', 'activation', 'connect', 'revoke', 'packages', 'started', 'search', 'friends', 'messages', 'message', 'notifications', 'notification', 'settings', 'setting', 'posts', 'post', 'photos', 'photo', 'create', 'pages', 'page', 'groups', 'group', 'events', 'event', 'games', 'game', 'saved', 'forums', 'forum', 'blogs', 'blog', 'articles', 'article', 'directory', 'products', 'product', 'market', 'admincp', 'admin', 'admins', 'modcp', 'moderator', 'moderators', 'moderatorcp', 'chat', 'ads', 'wallet', 'boosted', 'people', 'popular', 'movies', 'movie',  'api', 'apis', 'oauth', 'authorize');
		if(in_array(strtolower($username), $reserved_usernames)) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * valid_name
	 * 
	 * @param string $name
	 * @return boolean
	 */
	function valid_name($name) {
		if(preg_match('/[\'^??$%&*()}{@#~?><>,|=+??]/', $name)) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * valid_location
	 * 
	 * @param string $location
	 * @return boolean
	 */
	function valid_location($location) {
		if(preg_match("/^[\\p{L} \-,()0-9]+$/ui", $location)) {
			return true;
		} else {
			return false;
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

	if ( ! function_exists('theme_404'))
	{
		function theme_404($page_group = null, $controller = null) {
			$ci	=&	get_instance();

			if ($page_group == null) {
				$page_group = 'user';
			}

			$page_data = array();
			$page_data['page_name']              = "error-404";
			$page_data['page_title']             = __("Not Found");
			$page_data['page_icon']              = null;
			$page_data['query_params']           = null;

			if ($controller == null) {
				$controller = $ci->router->class;
			}
			$page_data['controller']           	 = $controller;

			$page_data['page_role']           	 = $ci->session->userdata('page_role');

			$ci->load->model('crud/Mnavigation');
			$navigation = $ci->Mnavigation->get_navigation($ci->session->userdata('role_id'), $page_group);
			$page_data['navigation']	 = $navigation;

			$template = "error/404.tpl";
			$ci->smarty->render_theme($template, $page_data);
		}
	}

	if ( ! function_exists('theme_403'))
	{
		function theme_403($page_group = null, $controller = null) {
			$ci	=&	get_instance();

			if ($page_group == null) {
				$page_group = 'user';
			}

			$page_data = array();
			$page_data['page_name']              = "error-403";
			$page_data['page_title']             = __("Not Authorized");
			$page_data['page_icon']              = null;
			$page_data['query_params']           = null;

			if ($controller == null) {
				$controller = $ci->router->class;
			}
			$page_data['controller']           	 = $controller;

			$page_data['page_role']           	 = $ci->session->userdata('page_role');

			$ci->load->model('crud/Mnavigation');
			$navigation = $ci->Mnavigation->get_navigation($ci->session->userdata('role_id'), $page_group);
			$page_data['navigation']	 = $navigation;

			var_dump($page_group);
			var_dump($navigation);

			$template = "error/403.tpl";
			$ci->smarty->render_theme($template, $page_data);
		}
	}

	if ( ! function_exists('theme_404_with_navigation'))
	{
		function theme_404_with_navigation($navigation, $controller = null) {
			$ci	=&	get_instance();

			$page_data = array();
			$page_data['page_name']              = "error-404";
			$page_data['page_title']             = __("Not Found");
			$page_data['page_icon']              = null;
			$page_data['query_params']           = null;

			if ($controller == null) {
				$controller = $ci->router->class;
			}
			$page_data['controller']           	 = $controller;

			$page_data['page_role']           	 = $ci->session->userdata('page_role');

			$page_data['navigation']	 = $navigation;

			$template = "error/404.tpl";
			$ci->smarty->render_theme($template, $page_data);
		}
	}

	if ( ! function_exists('theme_403_with_navigation'))
	{
		function theme_403_with_navigation($navigation, $controller = null) {
			$ci	=&	get_instance();

			$page_data = array();
			$page_data['page_name']              = "error-403";
			$page_data['page_title']             = __("Not Authorized");
			$page_data['page_icon']              = null;
			$page_data['query_params']           = null;

			if ($controller == null) {
				$controller = $ci->router->class;
			}
			$page_data['controller']           	 = $controller;

			$page_data['page_role']           	 = $ci->session->userdata('page_role');

			$page_data['navigation']	 = $navigation;

			$template = "error/403.tpl";
			$ci->smarty->render_theme($template, $page_data);
		}
	}

	if ( ! function_exists('replace_userdata'))
	{
		function replace_userdata($str, $REGEX_USERDATA='/{{(\w+)}}/') {
			$ci	=&	get_instance();

			$matches = null;
			while (preg_match($REGEX_USERDATA, $str, $matches)) {
				//use userdata
				$val = $ci->session->userdata($matches[1]);
				if (isset($val) && $val != null) {
					$str = str_replace($matches[0], $val, $str);
				}
				else {
					$str = str_replace($matches[0], "--" .$matches[1]. "--", $str);
				}
			}

			return $str;
		}
	}

	if ( ! function_exists('json_not_login'))
	{
		function json_not_login() {
			$data['error'] = 'not-login';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	

			exit;
		}

		function json_not_authorized() {
			$data['error'] = 'not-authorized';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	

			exit;
		}

		function json_not_implemented() {
			$data['error'] = 'not-implemented';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	

			exit;
		}
	}

?>