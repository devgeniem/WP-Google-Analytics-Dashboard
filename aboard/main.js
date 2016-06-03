window.dashboard = (function(window, document, $, data_options) {

	app = {};

	app.ids = null;
	app.blocks = {};
	app.charts = {};

	app.blocks.resolutions = function() {		
		var query = {
				"metrics": "ga:pageviews",
				"dimensions": "ga:screenResolution",
				"start-date": "30daysAgo",
				"end-date": "today",
				"sort": "-ga:pageviews",
				"filters": "ga:screenResolution!=(not set)"
		};
		app.getData(query, function(data) {
			var data_array = [
				["Browser", "Views"]
			];

			var max = Math.floor(2*app.$resolutions.data("width"));

			if(data_options.blocks.resolutions.max) {
				max = data_options.blocks.resolutions.max;
			}

			for(var i in data) {
				var temp_array = [
					data[i][0],
					parseInt(data[i][1]),
				];

				if(data_array.length <= max) {
					data_array.push(temp_array);
				}
				else {
					if(data_array.length == max+1) {
						data_array[max+1] = ["Other", temp_array[1]];
					}
					else {
						data_array[max+1][1] = temp_array[1]+data_array[max+1][1];
					}
				}
			}

			var blockwidth = app.$resolutions.width();
			var blockheight = app.$resolutions.height() - 10;

			var data_obj = google.visualization.arrayToDataTable(data_array);
			var options = {
				width: blockwidth,
				height: blockheight,
				backgroundColor: "none",
				legend: {
					position: "none"
				},
				colors: ["#3366CC", "#3F98B6", "#0687B3", "#3B77E4", "#557AF1"],
				tooltip: {
					trigger: "none"
				},
				hAxis: {
					textStyle:{color: '#e0e0e0'}
				},
				vAxis: {
					textStyle:{color: '#e0e0e0'},
					gridlines: {
						color: 'transparent'
					}
				},
				enableInteractivity: false
			};

			var temp = app.$resolutions.find(".content")[0];

			if(typeof app.charts.resolutions === 'undefined') app.charts.resolutions = new google.visualization.ColumnChart(temp);
			app.charts.resolutions.draw(data_obj, options);
		});
	};

	app.blocks.browsers = function() {		
		var query = {
				"metrics": "ga:pageviews",
				"dimensions": "ga:browser",
				"start-date": "30daysAgo",
				"end-date": "today",
				"sort": "-ga:pageviews"
		};
		app.getData(query, function(data) {
			var data_array = [
				["Browser", "Views"]
			];

			var max = Math.floor(2*app.$browsers.data("width"));

			if(data_options.blocks.browsers.max) {
				max = data_options.blocks.browsers.max;
			}

			for(var i in data) {
				var temp_array = [
					data[i][0],
					parseInt(data[i][1]),
				];

				if(data_array.length <= max) {
					data_array.push(temp_array);
				}
				else {
					if(data_array.length == max+1) {
						data_array[max+1] = ["Other", temp_array[1]];
					}
					else {
						data_array[max+1][1] = temp_array[1]+data_array[max+1][1];
					}
				}
			}

			var blockwidth = app.$browsers.width();
			var blockheight = app.$browsers.height() - 10;

			var data_obj = google.visualization.arrayToDataTable(data_array);
			var options = {
				width: blockwidth,
				height: blockheight,
				backgroundColor: "none",
				legend: {
					position: "none"
				},
				colors: ["#3366CC", "#3F98B6", "#0687B3", "#3B77E4", "#557AF1"],
				tooltip: {
					trigger: "none"
				},
				hAxis: {
					textStyle:{color: '#e0e0e0'}
				},
				vAxis: {
					textStyle:{color: '#e0e0e0'},
					gridlines: {
						color: 'transparent'
					}
				},
				enableInteractivity: false
			};

			var temp = app.$browsers.find(".content")[0];

			if(typeof app.charts.browsers === 'undefined') app.charts.browsers = new google.visualization.ColumnChart(temp);
			app.charts.browsers.draw(data_obj, options);
		});
	};

	app.blocks.systems = function() {		
		var query = {
				"metrics": "ga:pageviews",
				"dimensions": "ga:operatingSystem",
				"start-date": "30daysAgo",
				"end-date": "today",
				"sort": "-ga:pageviews"
		};
		app.getData(query, function(data) {
			var data_array = [
				["Browser", "Views"]
			];

			var max = Math.floor(2*app.$systems.data("width"));

			if(data_options.blocks.systems.max) {
				max = data_options.blocks.systems.max;
			}

			for(var i in data) {
				var temp_array = [
					data[i][0],
					parseInt(data[i][1]),
				];

				if(data_array.length <= max) {
					data_array.push(temp_array);
				}
				else {
					if(data_array.length == max+1) {
						data_array[max+1] = ["Other", temp_array[1]];
					}
					else {
						data_array[max+1][1] = temp_array[1]+data_array[max+1][1];
					}
				}
			}

			var blockwidth = app.$systems.width();
			var blockheight = app.$systems.height() - 10;

			var data_obj = google.visualization.arrayToDataTable(data_array);
			var options = {
				width: blockwidth,
				height: blockheight,
				backgroundColor: "none",
				legend: {
					position: "none"
				},
				colors: ["#3366CC", "#3F98B6", "#0687B3", "#3B77E4", "#557AF1"],
				tooltip: {
					trigger: "none"
				},
				hAxis: {
					textStyle:{color: '#e0e0e0'}
				},
				vAxis: {
					textStyle:{color: '#e0e0e0'},
					gridlines: {
						color: 'transparent'
					}
				},
				enableInteractivity: false
			};

			var temp = app.$systems.find(".content")[0];

			if(typeof app.charts.systems === 'undefined') app.charts.systems = new google.visualization.ColumnChart(temp);
			app.charts.systems.draw(data_obj, options);
		});
	};

	app.blocks.devices = function() {
		var query = {
				"metrics": "ga:pageviews",
				"dimensions": "ga:deviceCategory",
				"start-date": "30daysAgo",
				"end-date": "today",
				"sort": "-ga:pageviews"
		};

		app.getData(query, function(data) {
			var data_array = [
				["Device", "Views"]
			];

			for(var i in data) {
				data[i][0] = data[i][0].charAt(0).toUpperCase()+data[i][0].substr(1, data[i][0].length);

				var temp_array = [
					data[i][0],
					parseInt(data[i][1]),
				];

				data_array.push(temp_array);				
			}

			var blockwidth = app.$devices.width();
			var blockheight = app.$devices.height() - 10;

			var data_obj = google.visualization.arrayToDataTable(data_array);
			var options = {
				width: blockwidth,
				height: blockheight,
				backgroundColor: "none",
				legend: {
					position: "none"
				},
				colors: ["#3366CC", "#3F98B6", "#0687B3", "#3B77E4", "#557AF1"],
				tooltip: {
					trigger: "none"
				},
				hAxis: {
					textStyle:{color: '#e0e0e0'}
				},
				vAxis: {
					textStyle:{color: '#e0e0e0'},
					gridlines: {
						color: 'transparent'
					}
				},
				bar: {
					groupWidth: "40%"
				},
				enableInteractivity: false
			};

			var temp = app.$devices.find(".content")[0];

			if(typeof app.charts.devices === 'undefined') app.charts.devices = new google.visualization.ColumnChart(temp);
			app.charts.devices.draw(data_obj, options);
		});
	};

	app.blocks.activeUsers = function() {
		var query = {
			"metrics": "rt:activeUsers"
		};
		app.getRealTime(query, function(data) {
			var blockheight = app.$activeUsers.height();
			app.$activeUsers.find(".content").css({
				"line-height": blockheight+"px"
			});

			var temp = app.$activeUsers.find(".content");
			$(temp).text(data[0] ? data[0] : 0);
		});
	};

	app.blocks.locations = function() {
		var query = {
			"metrics": "ga:pageviews",
			"dimensions": "ga:city",
			"start-date": "30daysAgo",
			"end-date": "today"
		};
		app.getData(query, function(data) {
			var data_array = [["City", "Pageviews"]];

			for(var i in data) {
				var temp = [
					data[i][0],
					parseInt(data[i][1])
				];
				data_array.push(temp);
			}

			var blockwidth = app.$locations.width() + app.$locations.width()/2;
			var blockheight = app.$locations.height();

			var data_obj = google.visualization.arrayToDataTable(data_array);
			var options = {
				resolution: "provinces",
				width: blockwidth,
				height: blockheight,
				backgroundColor: "none",
				region: "FI",
				displayMode: "markers",
				markerOpacity: 0.8,
				defaultColor: "#38cdff",
				datalessRegionColor: "#4a4a4a",
				legend: "none",
				keepAspectRatio: false,
				sizeAxis: {
					minSize: blockwidth/150,
					maxSize: blockwidth/30
				},
				colorAxis: {
					colors: [
						"#66B0FF",
						"#3366CC"
					]
				},
				enableInteractivity: false,
				tooltip: {
					trigger: "none"
				}
			};

			var temp2 = app.$locations.find(".content")[0];
			if(typeof app.charts.locations === 'undefined') app.charts.locations = new google.visualization.GeoChart(temp2);
			app.charts.locations.draw(data_obj, options);

			app.$locations.find(".content").css({
				"margin-left": blockwidth/6*-1
			});
		});
	};

	app.blocks.siteViews = function() {
		var query = {
			"metrics": "ga:pageviews",
			"dimensions": "ga:date",
			"start-date": "7daysAgo",
			"end-date": "today",
		};
		app.getData(query, function(data) {
			var table = [
				["Date", "Views"],
			];
			
			for(var i in data) {
				var temp = [
					data[i][0],
					parseInt(data[i][1])
				];
				table.push(temp);
			}

			if(data.length === 0) table.push([0,0]);

			var data_obj = google.visualization.arrayToDataTable(table);

			var blockwidth = app.$siteViews.width() + app.$siteViews.width()/10;
			var blockheight = app.$siteViews.height();

			var options = {
				width: blockwidth,
				height: blockheight,
				legend: "none",
				backgroundColor: "none",
				axisTitlesPosition: "none",
				vAxis: {
					gridlines: {
						color: "transparent",
					},
					textStyle: {
						color: "#e0e0e0",
					},
				},
				hAxis: {
					textPosition: "none",
				},
				enableInteractivity: false
			};

			var temp2 = app.$siteViews.find(".content")[0];
			if(typeof app.charts.siteViews === 'undefined') app.charts.siteViews = new google.visualization.AreaChart(temp2);

			app.charts.siteViews.draw(data_obj, options);
		});
	};

	app.blocks.usage = function() {
		var query = {
			"metrics": "ga:pageViews", //tai sessions
			"start-date": "60daysAgo",
			"end-date": "30daysAgo",
		};
		app.getData(query, function(data1) {
			var query = {
				"metrics": "ga:pageViews", //tai sessions
				"start-date": "30daysAgo",
				"end-date": "today",
			};
			app.getData(query, function(data2) {
				var change = "";

				var pageviews = data2[0];
				if(pageviews >= 1000000) {
					pageviews = pageviews/1000000;
					pageviews = Math.floor(pageviews * 10) / 10;
					pageviews += "M";
				}
				else if(pageviews >= 100000) {
					pageviews = pageviews/1000;
					pageviews = Math.floor(pageviews * 10) / 10;
					pageviews += "K";
				}

				app.$usage.find(".content").text(pageviews);

				if(data1[0] && data2[0]) {
					data1 = parseInt(data1[0]);
					data2 = parseInt(data2[0]);

					var percent = (data1 - data2) / data1 * 100;
					percent = Math.round(percent * 100) / 100;
					percent = Math.abs(percent)+"%";
					var increase = (data1 <= data2) ? true : false;
					
					change = ((increase) ? "+" : "-")+percent;
				}



				var $temp = app.$usage.find(".content2");
				$temp.text(change);

				if(change.charAt(0) == "+") $temp.css({"color": "#56F13A"});
				else $temp.css({"color": "#D11815"});

				var blockheight = app.$usage.height();
				app.$usage.find(".content").css({
					"line-height": blockheight-blockheight/5+"px"
				});
				app.$usage.find(".content2").css({
					"line-height": blockheight+blockheight/5+"px"
				});
			});
		});
	};

	app.blocks.time = function() {
		var days = [
			"Sunnuntai",
			"Maanantai",
			"Tiistai",
			"Keskiviikko",
			"Torstai",
			"Perjantai",
			"Lauantai"
		];

		var date = new Date();
		var hr = date.getHours();
		var min = ( "0"+date.getMinutes() ).substr(-2);

		var day = days[date.getDay()];
		var month = date.getMonth()+1;

		var date_formatted = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear();

		app.$time.find(".content").text(hr+":"+min);
		app.$time.find(".desc").html(day+"<br>"+date_formatted);
	};

	app.blocks.sources = function() {
		var query = {
			"metrics": "ga:pageViews", //tai sessions
			"dimensions": "ga:source",
			"start-date": "30daysAgo",
			"end-date": "today",
			"sort": "-ga:pageviews"
		};
		app.getData(query, function(data) {
			var temp = app.$sources.find(".content");
			$(temp).empty();

			var max = Math.floor(4.5*app.$browsers.data("height"));

			if(data_options.blocks.browsers.max) {
				max = data_options.blocks.sources.max;
			}

			var data_array = ["asd", "abc"];

			for(var i in data) {
				var temp_array = [
					data[i][0],
					parseInt(data[i][1]),
				];

				if(data_array.length <= max) {
					data_array.push(temp_array);
					$(temp).append("<hr><p>"+data[i][0]+"</p><p>"+data[i][1]+"</p>");
				}
				else {
					if(data_array.length == max+1) {
						data_array[max+1] = ["Other", temp_array[1]];
						$(temp).append("<hr><p>Other</p><p>"+data[i][1]+"</p>");
					}
					else {
						data_array[max+1][1] = temp_array[1]+data_array[max+1][1];
						$(temp).find("p:last").text(data_array[max+1][1]);
					}
				}
			}
		});
	};

	app.blocks.avgSession = function() {
		var query = {
			"metrics": "ga:avgSessionDuration",
			"start-date": "30daysAgo",
			"end-date": "today"
		};
		app.getData(query, function(data) {
			var blockheight = app.$avgSession.height();
			app.$avgSession.find(".content").css({
				"line-height": blockheight+"px"
			});

			if(data.length === 0) data = 0;
			else data = data[0];
			
			data = app.toTime(data);

			var temp = app.$avgSession.find(".content");
			$(temp).text(data);
		});
	};

	app.blocks.avgLoad = function() {
		var query = {
			"metrics": "ga:avgPageLoadTime",
			"start-date": "30daysAgo",
			"end-date": "today"
		};
		app.getData(query, function(data) {
			var blockheight = app.$avgLoad.height();
			app.$avgLoad.find(".content").css({
				"line-height": blockheight+"px"
			});

			if(data.length === 0) data = 0;
			else data = data[0];
			
			data = Math.round(data * 1000) / 1000;
			var temp = app.$avgLoad.find(".content");
			$(temp).text(data+"s");
		});
	};

	/**
	 * Format seconds to HH:MM:SS
	 * @param  {Integer} Number of seconds
	 * @return {String} Formatted HH:MM:SS
	 */
	app.toTime = function(sec_num) {
		sec_num = parseInt(sec_num, 10);
		var hours   = Math.floor(sec_num / 3600);
		var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
		var seconds = sec_num - (hours * 3600) - (minutes * 60);

		if (hours   < 10) {hours   = "0"+hours;}
		if (minutes < 10) {minutes = "0"+minutes;}
		if (seconds < 10) {seconds = "0"+seconds;}
		return hours+':'+minutes+':'+seconds;
	};

	/**
	 * Get regular analytics data
	 * @param  {Object} Query to retrieve data
	 * @param  {Function} Function to call with data when complete
	 */
	app.getData = function(query, callback) {
		query = JSON.stringify(query);
		$.post(window.location, {"data_query": query}, function(data) {
			try {
				data = JSON.parse(data);
				callback(data);
			}
			catch(err) {
				callback([]);
			}
		});
	};

	/**
	 * Get realtime analytics data
	 * @param  {Object} Query to retrieve data
	 * @param  {Function} Function to call with data when complete
	 */
	app.getRealTime = function(query, callback) {
		query = JSON.stringify(query);
		$.post(window.location, {"real_query": query}, function(data) {
			try {
				data = JSON.parse(data);
				callback(data);
			}
			catch(err) {
				callback([]);
			}
		});
	};

	/**
	 * Set listeners for app
	 */
	app.listeners = function() {
		$("body").dblclick(function() {
			if((window.fullScreen) || (window.innerWidth == screen.width && window.innerHeight == screen.height)) {
				exitFullscreen();
			}
			else {
				launchFullscreen(document.documentElement);
			}
		});

		function launchFullscreen(element) {
			if(element.requestFullscreen) {
				element.requestFullscreen();
			} else if(element.mozRequestFullScreen) {
				element.mozRequestFullScreen();
			} else if(element.webkitRequestFullscreen) {
				element.webkitRequestFullscreen();
			} else if(element.msRequestFullscreen) {
				element.msRequestFullscreen();
			}
		}

		function exitFullscreen() {
			if(document.webkitExitFullscreen) {
				document.webkitExitFullscreen();
			} else if(document.mozCancelFullscreen) {
				document.mozCancelFullscreen();
			} else if(document.exitFullscreen) {
				document.exitFullscreen();
			}
		}
	};

	/**
	 * Calculate new width and height for a block
	 * @param  {Object} Element to use for calculation
	 * @return {Object} Width and height values for element
	 */
	app.getSize = function(elem) {
		var width = $(elem).data("width");
		var height = $(elem).data("height");
		var blocksizes = app.getBlockSizes();
		var vals = {};

		vals.width = blocksizes*width + 10*(width-1);
		vals.height = blocksizes*height + 10*(height-1);
		
		return vals;
	};

	/**
	 * Calculate new position for a block
	 * @param  {Object} Element to use for calculation
	 * @return {Object} Top and left values for element
	 */
	app.getPos = function(elem) {
		var posx = elem.data("posx");
		var posy = elem.data("posy");
		var blocksizes = app.getBlockSizes();
		var vals = {};

		vals.posx = blocksizes*posx + 10*posx;
		vals.posy = blocksizes*posy + 10*posy;

		return vals;
	};

	/**
	 * Calculate minimum block size
	 * @return {Integer} Calculated width in pixels
	 */
	app.getBlockSizes = function() {
		var max = app.maxblocks;
		var docwidth = $(document).width() - (max*10) - 10;
		var blocksizes = Math.floor(docwidth/max);

		return blocksizes;
	};

	/**
	 * Set styles for all blocks
	 */
	app.setStyles = function() {

		if(data_options.logo !== null) {
			app.$logo.css({
				"background-image": "url('"+data_options.logo+"')"
			});
		}
		else app.$logo.hide();

		for(var i in data_options.blocks) {
			if(data_options.blocks[i] !== null) {
				$("#"+i).data("posx", data_options.blocks[i].x);
				$("#"+i).data("posy", data_options.blocks[i].y);

				$("#"+i).data("width", data_options.blocks[i].w);
				$("#"+i).data("height", data_options.blocks[i].h);
			}
			else $("#"+i).hide();
		}

		$(".block").each(function() {
			var size = app.getSize($(this));
			var pos = app.getPos($(this));
			$(this).css({
				"width": size.width,
				"height": size.height,
				"left": pos.posx,
				"top": pos.posy,
				"font-size": app.getBlockSizes()*0.08
			});
		});

		var blockheight = app.$time.height();
		app.$time.find(".content").css({
			"line-height": blockheight-blockheight/4+"px"
		});
	};

	/**
	 * Set a timer for a function
	 * @param {Function} Function to call
	 * @param {Integer} Timer in milliseconds
	 */
	app.setTimer = function(func, time) {
		window.setInterval(function() {
			func();
		}, time);
	};

	/**
	 * Call first time data and set timers for enabled blocks
	 */
	app.viewConstructors = function() {
		for(var i in data_options.blocks) {
			if(i !== null && i !== "logo") {
				app.blocks[i]();

				if(data_options.blocks[i] !== null && data_options.blocks[i].timer) {
					app.setTimer(app.blocks[i], data_options.blocks[i].timer);
				}
			}
		}
	};

	/**
	 * Cache elements that are used in scripts
	 */
	app.cache = function() {
		app.$browsers = $("#browsers");
		app.$activeUsers = $("#activeUsers");
		app.$locations = $("#locations");
		app.$siteViews = $("#siteViews");
		app.$usage = $("#usage");
		app.$devices = $("#devices");
		app.$sources = $("#sources");
		app.$systems = $("#systems");
		app.$resolutions = $("#resolutions");
		app.$avgSession = $("#avgSession");
		app.$avgLoad = $("#avgLoad");

		app.$time = $("#time");
		app.$logo = $("#logo");
	};
	
	/**
	 * Initialize script
	 */
	app.init = function() {
		app.cache();

		app.maxblocks = data_options.maxblocks ? data_options.maxblocks: 6;
		app.setStyles();

		app.viewConstructors();
		app.listeners();
	};

	$(document).ready( app.init );

	return app;

})(window, document, jQuery, data_options);
