<?php
/**
 * Plugin name: Google Analytics Dashboards
 * Plugin URI:  
 * Description: Google Analytics Dashboards integrated into Wordpress.
 * Author: Arttu Mäkipörhölä & Joel Koch
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Version: 1.0
 */

namespace ga_dash;

defined("ABSPATH") or die("No");

class dashboard {
	public static function init() {
		require_once(__DIR__."/class-views.php");
		require_once(__DIR__."/class-data.php");
		require_once(__DIR__."/templater.php");
		add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );


		self::createTable();
		add_action( 'admin_menu', array(__CLASS__,"add_menu"));
	}

	public static function updateJSON($json) {
		global $wpdb;
		$json = [
			"file" => $json
		];
		$table_name = $wpdb->prefix."ga_auth";
		$table_name2 = $wpdb->prefix."ga_dashboard";
		$table_name3 = $wpdb->prefix."ga_dashboard_access";
		$wpdb->query("DELETE FROM $table_name");
		$wpdb->query("DELETE FROM $table_name2");
		$wpdb->query("DELETE FROM $table_name3");
		$wpdb->insert($table_name, $json);
	}

	public static function addAccess($userid, $dashid) {
		global $wpdb;
		$data = [
			"user_id" => $userid,
			"dashboard_id" => $dashid,
		];
		$table_name = $wpdb->prefix."ga_dashboard_access";
		$wpdb->insert($table_name, $data);
	}

	public static function delAccess($userid, $dashboard_id) {
		global $wpdb;
		$data = [
			"user_id" => $userid,
			"dashboard_id" => $dashboard_id
		];

		$table_name = $wpdb->prefix."ga_dashboard_access";
		$wpdb->delete($table_name, $data);
	}

	public static function addDashboard($name, $viewid) {
		global $wpdb;
		$data = [
			"name" => $name,
			"viewid" => $viewid,
		];
		$table_name = $wpdb->prefix."ga_dashboard";
		$wpdb->insert($table_name, $data);

		$default = file_get_contents(__DIR__."/default.json");
		$default = json_decode($default, true);
		$default["id"] = $wpdb->insert_id;

		$object = json_encode($default, JSON_PRETTY_PRINT);

		$page = [
			"post_content" => $object,
			"post_title" => $name,
			"post_type" => "page",
			"post_status" => "publish",
		];
		$page_id = wp_insert_post($page);
		update_post_meta($page_id, '_wp_page_template', './aboard/index.php');
	}
	
	public static function delDashboard($dashboard_id) {
		$dashboard_id = intval($dashboard_id);

		global $wpdb;
		$data = [
			"id" => $dashboard_id
		];

		$table_name = $wpdb->prefix."ga_dashboard";
		$dashboard_name = $wpdb->get_row("SELECT name FROM $table_name WHERE id=$dashboard_id");
		$wpdb->delete($table_name, $data);

		$post = get_page_by_title($dashboard_name->name);
		wp_delete_post($post->ID);
	}

	public static function createTable() {
		global $wpdb;
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		$collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix."ga_dashboard";
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			name varchar(50) COLLATE utf8_swedish_ci NOT NULL,
			viewid int(11) NOT NULL,
			PRIMARY KEY id (id)
		) $collate;";
		dbDelta($sql);

		$table_name = $wpdb->prefix."ga_dashboard_access";
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			dashboard_id int(11) NOT NULL,
			PRIMARY KEY id (id)
		) $collate;";
		dbDelta($sql);

		$table_name = $wpdb->prefix."ga_auth";
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			file blob NOT NULL,
			PRIMARY KEY id (id)
		) $collate;";
		dbDelta($sql);
	}

	public static function add_menu() {
		add_menu_page(
			'Google Analytics Dashboard',
			'GA Dashboards',
			'manage_options',
			'ga_dashboard/display.php',
			'',
			'',
			6
		);
	}
}

dashboard::init();
