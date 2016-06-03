<?php

class analytics {

	private static $client;
	private static $analytics;
	private static $token;
	private static $id;

	public function __construct($id) {
		set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/google-api-php-client/src");
		require_once __DIR__."/google-api-php-client/vendor/autoload.php";

		self::$id = "ga:".$id;

		self::auth();
		self::setToken();
	}

	private static function auth() {
		global $wpdb;

		$table_name = $wpdb->prefix."ga_auth";
		$query = "SELECT * FROM $table_name";
		$auth = $wpdb->get_row($query);

		$temp_file = tempnam(sys_get_temp_dir(), "auth");
		file_put_contents($temp_file, $auth->file);

		self::$client = new Google_Client();
		self::$client->setAuthConfig($temp_file);
		self::$client->setApplicationName("data");

		self::setScopes();

		self::$analytics = new Google_Service_Analytics(self::$client);
	}

	private static function setScopes() {
		self::$client->addScope("https://www.googleapis.com/auth/analytics.readonly");
	}

	private static function setToken() {
		if (self::$client->isAccessTokenExpired()) {
			self::$client->refreshTokenWithAssertion();
		}
		$token = self::$client->getAccessToken();
		self::$token = $token["access_token"];
	}

	public static function getData($obj) {
		$obj = json_decode(stripslashes($obj));
		$optional = array();
		foreach($obj as $key => $val) {
			if($key == "start-date") $start_date = $val;
			elseif($key == "end-date") $end_date = $val;
			elseif($key == "metrics") $metrics = $val;
			else $optional[$key] = $val;
		}

		$data = self::$analytics->data_ga->get(
			self::$id,
			$start_date,
			$end_date,
			$metrics,
			$optional
		);

		if(isset($data->rows)) $data = json_encode($data->rows);
		else $data = "[]";

		return $data;
	}

	public static function getRealtime($obj) {
		$obj = stripslashes($obj);
		$url = "https://www.googleapis.com/analytics/v3/data/realtime?access_token=".self::$token;
		$url .= "&ids=".self::$id;

		$obj = json_decode($obj);

		foreach($obj as $key => $val) {
			$url .= "&$key=$val";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($data);

		if(isset($data->rows)) $data = json_encode($data->rows);
		else $data = "[]";

		return $data;
	}
}
