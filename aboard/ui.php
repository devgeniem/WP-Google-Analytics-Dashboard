<!doctype html>
<html>
<head>
	<title>Dashboard</title>
	<meta charset="UTF-8">
		<?php 
			echo "<style>";
			require __DIR__."/main.css";
			echo "</style>";
		?>
	</style>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<script>
		google.charts.load("current", {"packages":["corechart", "geochart"]});
	</script>

	<?php
		echo "<script>";
		echo "var data_options=".$wp_query->post->post_content.";";

		require __DIR__."/main.js";
		echo "</script>";
	?>
</head>
<body>

	<div id="logo" class="block"></div>

	<div id="time" class="block">
		<div class="content"></div>
		<div class="desc"></div>
	</div>
	
	<div id="activeUsers" class="block">
		<div class="title">Aktiiviset käyttäjät</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc"></div>
	</div>

	<div id="usage" class="block">
		<div class="title">Näyttökerrat</div>
		<div class="content"><div class="loader"></div></div>
		<div class="content2"></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>
	
	<div id="devices" class="block">
		<div class="title">Laitteet</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="locations" class="block">
		<div class="title">Sijainnit</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="siteViews" class="block">
		<div class="title">Näyttökerrat</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 7 päivää</div>
	</div>

	<div id="browsers" class="block">
		<div class="title">Selaimet</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="sources" class="block">
		<div class="title">Lähteet</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="systems" class="block">
		<div class="title">Käyttöjärjestelmät</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="resolutions" class="block">
		<div class="title">Resoluutiot</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="avgSession" class="block">
		<div class="title">Keskimääräinen istuntopituus</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

	<div id="avgLoad" class="block">
		<div class="title">Keskimääräinen latausaika</div>
		<div class="content"><div class="loader"></div></div>
		<div class="desc">Viimeiset 30 päivää</div>
	</div>

</body>
</html>