<?php
	if(file_exists($_FILES["json"]["tmp_name"])) {
		$data = file_get_contents($_FILES["json"]["tmp_name"]);
		ga_dash\dashboard::updateJSON($data);
	}

	if(!empty($_POST["view"]) && !empty($_POST["name"])) {
		ga_dash\dashboard::addDashboard($_POST["name"], $_POST["view"]);
	}

	if(!empty($_POST["user"]) && !empty($_POST["dashboard"])) {
		ga_dash\dashboard::addAccess($_POST["user"], $_POST["dashboard"]);
	}

	if(!empty($_POST["del_user"]) && !empty($_POST["del_dashboard"])) {
		ga_dash\dashboard::delAccess($_POST["del_user"], $_POST["del_dashboard"]);
	}

	if(!empty($_POST["del_dashboard"]) && empty($_POST["del_user"])) {
		ga_dash\dashboard::delDashboard($_POST["del_dashboard"]);
	}

	try {
		$views = new getViews();
		$temp = $views->getManagement();
		$view_data = json_encode($temp);

		$ga_data = [
			"users" => [],
			"dashboards" => [],
			"access" => []
		];

		$users = get_users();
		foreach ($users as $user) {
			$name = $user->data->user_login;		
			$id = $user->data->ID;

			$ga_data["users"][$id] = $name;
		}

		$boards = $views->getDashboards();
		foreach($boards as $board) {
			$name = $board->name;		
			$id = $board->id;

			$ga_data["dashboards"][$id] = $name;
		}


		$boards = $views->getAccess();
		foreach($boards as $board) {
			$name = $board->name;		
			$id = $board->id;

			$user_id = $board->user_id;
			$user = get_userdata($user_id);
			$user_login = $user->user_login;

			if(!isset($ga_data["access"][$id])) {
				$ga_data["access"][$id] = [
					"name" => $name,
					"users" => []
				];
			}
			if(isset($user_id)) $ga_data["access"][$id]["users"][$user_id] = $user_login;
		}		
	}
	catch(Exception $e) {
		$ga_error = true;
		$view_data = "[]";

		$ga_data = [
			"users" => [],
			"dashboards" => [],
			"access" => [],
		];
	}
?>

<script>
	var data = {
		views: JSON.parse('<?php echo $view_data ?>'),
		users: JSON.parse('<?php echo json_encode($ga_data["users"]); ?>'),
		dashboards: JSON.parse('<?php echo json_encode($ga_data["dashboards"]); ?>'),
		access: JSON.parse('<?php echo json_encode($ga_data["access"]); ?>'),
	}

	window.dashboard_menu = (function(window, document, $, data) {
		app = {};
		cache = {};

		app.htmlDashboards = function() {
			for(a in data["views"]) {
				$temp = $("<option>"+data["views"][a][0]+"</option>");
				$temp.val(data["views"][a][1]);

				cache.$account.append($temp);

				for(b in data["views"][a][2]) {
					$temp = $("<option>"+data["views"][a][2][b][0]+"</option>");
					$temp.val(data["views"][a][2][b][1]);
					$temp.attr("data-parent", data["views"][a][1]);

					cache.$prop.append($temp);

					for(c in data["views"][a][2][b][2]) {
						$temp = $("<option>"+data["views"][a][2][b][2][c][0]+"</option>");
						$temp.val(data["views"][a][2][b][2][c][1]);
						$temp.attr("data-parent", data["views"][a][2][b][1]);

						cache.$view.append($temp);
					}
				}
			}
		};

		app.changeViews = function(option) {

			if(option == 0) {
				var temp = cache.$account.find("option:selected").val();
				cache.$prop.children().hide();
				cache.$prop.find("option:first-child").show();
				cache.$prop.find("option:first-child").prop("selected", true);
				cache.$prop.find("option[data-parent='"+temp+"']").show();
			}
			
			var temp = cache.$prop.find("option:selected").val();
			cache.$view.children().hide();
			cache.$view.find("option:first-child").show();
			cache.$view.find("option:first-child").prop("selected", true);
			cache.$view.find("option[data-parent='"+temp+"']").show();

		};

		app.htmlAccess = function() {
			for(i in data["users"]) {
				var $temp = $("<option></option>");
				$temp.text(data["users"][i]);
				$temp.val(i);

				cache.$user_list.append($temp);
			}
			for(i in data["dashboards"]) {
				var $temp = $("<option></option>");
				$temp.text(data["dashboards"][i]);
				$temp.val(i);

				cache.$dashboard_list.append($temp);
			}
		}

		app.htmlListAccess = function() {
			for (a in data["access"]) {
				var $temp = $("<tr></tr>");
				$temp.append("<td>"+data["access"][a]["name"]+"</td><td></td><td><button data-a='"+a+"'>Delete</button></td>");

				cache.$list_access.append($temp);

				for(b in data["access"][a]["users"]) {
					var $temp = $("<tr></tr>");
					$temp.append("<td></td><td>"+data["access"][a]["users"][b]+"</td><td><button class='userbutton' data-a='"+a+"' data-b='"+b+"'>Delete</button></td>");

					cache.$list_access.append($temp);
				}
			}
		}

		app.cache = function() {
			cache.$content = $(".content");

			cache.$account = cache.$content.find("select[name='account']");
			cache.$prop = cache.$content.find("select[name='prop']");
			cache.$view = cache.$content.find("select[name='view']");
			cache.$file_input = cache.$content.find("input[type='file']");

			cache.$user_list = cache.$content.find("select[name='user']");
			cache.$dashboard_list = cache.$content.find("select[name='dashboard']");

			cache.$list_access = cache.$content.find("#list-access");
		};

		app.listeners = function() {
			cache.$account.change(function() { app.changeViews(0); });
			cache.$prop.change(function() { app.changeViews(1); });

			cache.$file_input.change(function() {
				cache.$file_input.parent().submit();
			});

			cache.$list_access.find("button").click(function() {
				if(confirm("Are you sure?")) {
					if($(this).data("b")) {
						var data = {
							"del_user": $(this).data("b"),
							"del_dashboard": $(this).data("a")
						}
					}
					else {
						var data = {
							"del_dashboard": $(this).data("a")
						}
					}
					$.post(window.location, data, function() {
						window.location.reload();
					});
				}
			});
		};

		app.init = function() {
			app.cache();

			if(data["views"].length != 0) {
				app.htmlDashboards();
				app.changeViews(0);

				app.htmlAccess();

				app.htmlListAccess();
			}
			else {
				cache.$content.children().hide();
				cache.$content.find("#form-json").show();
			}

			app.listeners();
		};

		$(document).ready(function() { app.init(); });

		return {app: app, cache: cache};

	})(window, document, jQuery, data);
</script>

<style>
	.content * {
		margin: 0;
		padding: 0;
		box-sizing: border-box;

		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
	}
	.content {
		width: 100%;
		max-width: 500px;
	}

	#form-dashboard * {
		width: 100%;
	}

	#form-json label {
		width:50%;
		margin:0 auto;
	}

	.content input, .content select, #form-json label {
		background-color: white;
		width: 100%;
		display: block;
		text-align: center;
		border: 1px darkgray solid;
		margin-top: 5px;
		text-decoration: none;
		height:33px;
		padding:0 5px 0 5px;
	}

	.content select {
		background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAvxJREFUeJzt3E2ID3Ecx/G3xw152oM8JQ+Rh5DDlptyECfl6OAiOXo4iYOLkps4Ui5byMFDe6AUkQMupEhKREJ5WLLlYZfDcFHs/uc/8/vOzP/9qs91Z+bzmXbb+c8uSJIkSZIkSZIkSZIkSZIkSZIkSZIkSZIkSZIkSZKkTrMYOAf0Az/Nf/MROAssytV0BS0D3hNfbN3yDliSo+/K6SO+zLrmYo6+WzKq7AMAA8CEBMdpogFgUpkHGF3mF/9tMMExmupH2QdIcQP0JThGUzWiu9nAC+J/ntYtz4FZOfqupJX4K2Ar+QisyNV0hW0AvhNfbtXzDVifs+PK2058wVXPttzt1sQh4kuuag620Wut9BJfdtVyqq1Ga2Y8cJ340quSq8C4dgqto+nAI+LLj84DYGqbXdbWAuAN8SNE5RUwr+0Wa64H+EL8GKnzGVhTQH+NsJnsM4PoUVLlB7CpkOYaZBfxw6TKzoI6a5yjxI9Tdo4U1lYDjQbOEz9SWTlDmncwam0CcJv4sYrOTaCrwJ4abQbwlPjRispjoLvQhjrAUprxMulbYGHB3XSMdcBX4kfMmwFgbeGtdJitwBDxY7aaQWBLCX10pAPED9pq9pTSRAc7SfyoI82xkjroaGOBK8SPO1wukOZt6440BbhP/Mj/yh1gYmlXLwDmAi+JH/vvPCV7fqEEVgOfiB/9T96TPbdQQhupxmvmX8meVyjADmLHHyJ7TqFAh4m7AfYnuD4NYxRwmvTjn0hxcRqZLuAG6ca/TPZcQhXSTfaxa9nj3wMmJ7omtWgR2cevZY3/ApiT7GqUy1qyj2GLHr8fWJXwOtSGLRT7mvl3sj9vV43spbgbYHvic1dBjtP++IeSn7UKMwa4RP7xe9Ofsoo2EbhL6+NfI/szdjXATOAZIx//ITAt4kRVnuXAB4Yf/zUwP+YUVbYe/v8yyROyG0UNNgXYR/b6Vj/Zd4VbwG78n8aSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJCnQL74K1b7KD2ipAAAAAElFTkSuQmCC');

		background-repeat: no-repeat;
		background-position: 98% 50%;
		background-size: 23px;
	}

	.content input[type="submit"], #form-json label {
		cursor: pointer;
		padding: 2px;
		border: 1px solid #3079ed;
		color: white;
		text-shadow: 0 1px rgba(0,0,0,0.1);
		background-color: #4d90fe;
		background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
	}

	.content hr{
		margin-bottom: 5px;
		margin-top: 5px;
	}

	.content table{
		width:100%;
		background-color: white;
		border-collapse: collapse;
		border:1px darkgray solid;
	}

	.content table tr:nth-child(odd) {
		background-color:#E8E8E8;
	}

	.content table tr:last-child {
		border-bottom:0;
	}
	.content table td{
		height:33px;
		line-height: 33px;
		padding-left:5px;
		padding-right:5px;
		text-align:right;
	}

	.content table tr td:first-child {
		font-weight: bold;
		text-align: left;
	}

	.content button {
		width:70px;
		display: inline-block;
		text-align: center;
		border: 1px darkgray solid;
		margin-top: 5px;
		text-decoration: none;

		cursor: pointer;
		padding: 2px;
		border: 1px solid #3079ed;
		color: white;
		text-shadow: 0 1px rgba(0,0,0,0.1);
		background-color: #4d90fe;
		background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
	}
	td .userbutton {
		background: #60B400;
		border: 1px solid #3D9F00;
		background-image: -webkit-linear-gradient(top,#60B400,#56A300);
	}
</style>

<div class="content">
	<form method="post" enctype="multipart/form-data" id="form-json">
		Send key file (json format)<br>
		<input type="file" accept=".json" name="json" id="json" style="display:none;">
		<label for="json">Send key file</label>
	</form>

	<hr>

	<form method="post" id="form-dashboard">
		<input type="text" placeholder="Dashboard name" name="name" required>
		
		<select name="account"></select>
		<select name="prop" required><option style="display:none;" disabled></option></select>
		<select name="view" required><option style="display:none;" disabled></option></select>
		
		<input type="submit" value="Create Dashboard">
	</form>

	<hr>

	<form method="post" id="form-access">	
		<select name="user" required></select>
		<select name="dashboard" required></select>

		<input type="submit" value="Save">
	</form>

	<hr>
	<table id="list-access"></table>
</div>
