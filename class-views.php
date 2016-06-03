<?php

class getViews {

	private static $client;
	private static $analytics;

	public function __construct() {
		set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/google-api-php-client/src");
		require_once __DIR__."/google-api-php-client/vendor/autoload.php";
		self::auth();
	}

	public static function getAccess() {
		global $wpdb;

		$table_name = $wpdb->prefix."ga_dashboard";
		$table_name2 = $wpdb->prefix."ga_dashboard_access";
		$query = "SELECT name, $table_name.id, user_id, viewid FROM $table_name LEFT JOIN $table_name2 ON $table_name2.dashboard_id = $table_name.id ORDER BY name";

		return $wpdb->get_results($query, OBJECT);
	}

	public static function getDashboards($id = false) {
		global $wpdb;

		if(!$id) {
			$table_name = $wpdb->prefix."ga_dashboard";
			$query = "SELECT name, id FROM $table_name";
		}
		else {
			$table_name = $wpdb->prefix."ga_dashboard_access";
			$table_name2 = $wpdb->prefix."ga_dashboard";
			$query = "SELECT name, viewid FROM $table_name INNER JOIN $table_name2 ON $table_name2.id = $table_name.dashboard_id WHERE user_id=$id";
		}

		return $wpdb->get_results($query, OBJECT);
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

	public static function getManagement() {
		$management_list = self::$analytics->management_accounts->listManagementAccounts();
		$management_list->getItems();

		$tree = [];
		$a = 0;
		foreach($management_list as $temp) {

			$account_id = $temp->getId();
			$tree[] = [$temp->getName(), $account_id, []];
			
			$web_properties = self::$analytics->management_webproperties->listManagementWebproperties($account_id);
			$b = 0;
			foreach($web_properties as $temp2) {

				$propid = $temp2->getId();
				$tree[$a][2][$b] = [$temp2->getName(), $propid, []];

				$profile = self::$analytics->management_profiles->listManagementProfiles($account_id, $propid);
				
				$views = $profile->getItems();

				$c = 0;
				foreach($views as $temp3) {
					$tree[$a][2][$b][2][$c][0] = $temp3->getName();
					$tree[$a][2][$b][2][$c][1] = $temp3->getId();
					$c++;
				}
				$b++;
			}
			$a++;
		}
		return $tree;
	}
}
