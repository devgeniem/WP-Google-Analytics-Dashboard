<?php
require('./wp-load.php');
$user = wp_get_current_user();

$views = new getViews();
$data = json_decode($wp_query->post->post_content);
$cont = false;
foreach ($views::getAccess() as $row) {
	if($row->user_id == $user->ID && $row->id == $data->id) {
		$viewid = $row->viewid;
		$cont = true;
		break;
	}
}

if($user->ID && $cont) {
	if(isset($_POST["real_query"]) || isset($_POST["data_query"])) {

		$analytics = new analytics($viewid);
		if(isset($_POST["real_query"])) {
			echo $analytics::getRealtime($_POST["real_query"]);
		}
		else {
			echo $analytics::getData($_POST["data_query"]);
		}

	}
	else {

		$post_id = $wp_query->post->ID;
		$img_url = get_the_post_thumbnail_url($post_id);

		require plugin_dir_path(__FILE__)."/ui.php";
		
	}
}
else if(!$user->ID){
	$url = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	header("Location: ".get_home_url()."/wp-login.php?redirect_to=$url");
}
else {
	echo "You do not have access to this dashboard!<br>";
	echo "<a href='".wp_logout_url()."'>Logout</a>";
}
