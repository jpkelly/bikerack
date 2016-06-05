<?php 
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$date = new DateTime();
$epoch = $date->getTimestamp();

// SOUTH BOUND DATA
$sb_maps_api_url = "https://maps.googleapis.com/maps/api/directions/json?origin=37.876152,-122.511077&destination=37.805310,-122.473599&departure_time=".$epoch."&key=<KEY>";
$sb_returned_content = get_data($sb_maps_api_url);

$sb_json_a=json_decode($sb_returned_content,true);
$sb_distance = $sb_json_a[routes][0][legs][0][distance][text];
$sb_dur_txt = $sb_json_a[routes][0][legs][0][duration][text];
$sb_dur_val = $sb_json_a[routes][0][legs][0][duration][value];


if (isset($sb_json_a[routes][0][legs][0][duration_in_traffic][text]))
  {
  	$sb_dur_traffic_txt = $sb_json_a[routes][0][legs][0][duration_in_traffic][text];
  	$sb_dur_traffic_val = $sb_json_a[routes][0][legs][0][duration_in_traffic][value];
  }
else
  {
  	$sb_dur_traffic_txt = $sb_dur_txt;
  	$sb_dur_traffic_val = $sb_json_a[routes][0][legs][0][duration][value];
  }

$sb_dur_traffic_txt_a = split ("\ ", $sb_dur_traffic_txt);
$sb_dur_traffic_txt_int = (int)$sb_dur_traffic_txt_a[0];

$sb_dur_txt_a = split ("\ ", $sb_dur_txt);
$sb_dur_txt_int = (int)$sb_dur_txt_a[0];

if ($sb_dur_traffic_txt_int == $sb_dur_txt_int) {
		$css_text_color = "#00b300";
	} elseif ($sb_dur_traffic_txt_int > $sb_dur_txt_int) {
		$css_text_color = "#ff0000";
	}

// NORTH BOUND DATA
$nb_maps_api_url = "https://maps.googleapis.com/maps/api/directions/json?origin=37.805472,-122.473531&destination=37.884883,-122.516651&departure_time=".$epoch."&key=<KEY>";
$nb_returned_content = get_data($nb_maps_api_url);

$nb_json_a=json_decode($nb_returned_content,true);
$nb_distance = $nb_json_a[routes][0][legs][0][distance][text];
$nb_dur_txt = $nb_json_a[routes][0][legs][0][duration][text];
$nb_dur_val = $nb_json_a[routes][0][legs][0][duration][value];


if (isset($nb_json_a[routes][0][legs][0][duration_in_traffic][text]))
  {
  	$nb_dur_traffic_txt = $nb_json_a[routes][0][legs][0][duration_in_traffic][text];
  	$nb_dur_traffic_val = $nb_json_a[routes][0][legs][0][duration_in_traffic][value];
  }
else
  {
  	$nb_dur_traffic_txt = $nb_dur_txt;
  	$nb_dur_traffic_val = $nb_json_a[routes][0][legs][0][duration][value];
  }

$nb_dur_traffic_txt_a = split ("\ ", $nb_dur_traffic_txt);
$nb_dur_traffic_txt_int = (int)$nb_dur_traffic_txt_a[0];

$nb_dur_txt_a = split ("\ ", $nb_dur_txt);
$nb_dur_txt_int = (int)$nb_dur_txt_a[0];

if ($nb_dur_traffic_txt_int == $nb_dur_txt_int) {
		$css_text_color = "#00b300";
	} elseif ($nb_dur_traffic_txt_int > $nb_dur_txt_int) {
		$css_text_color = "#ff0000";
	}


//DATALOGGING

// $datalogurl = "https://data.sparkfun.com/input/WGwQbWvOw7C7OWAdo9dY?private_key=<KEY>&duration_in_traffic_text=".$duration_in_traffic_text_int."&duration_in_traffic_value=".$duration_in_traffic_value."&duration_text=".$duration_text_int."&duration_value=".$duration_value;

$datalogurl = "http://data.sparkfun.com/input/YGoyGv65m0Cm51aAxOK7?private_key=<KEY>&nb_dur_traffic_txt=".$nb_dur_traffic_txt_int."&nb_dur_traffic_val=".$nb_dur_traffic_val."&nb_dur_txt=".$nb_dur_txt_int."&nb_dur_val=".$nb_dur_val."&sb_dur_traffic_txt=".$sb_dur_traffic_txt_int."&sb_dur_traffic_val=".$sb_dur_traffic_val."&sb_dur_txt=".$sb_dur_txt_int."&sb_dur_val=".$sb_dur_val;

$datalogurl_return = get_data($datalogurl);

?>

<!DOCTYPE html>
<html>
<head>
	<title>bikerack</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://www.google.com/jsapi"></script>
</head>
<body>
<?php echo $date->getTimestamp(); ?>
<br>
<?php echo date('F jS h:i:s A'); ?> ---- Page will reload in <span id="countdown"></span> seconds.
<br>

<div style="width: 800px; float: left; margin-right: 50px; word-wrap: break-word;">
	<h3 style="margin-left: 100px;" >South Bound Traffic</h3>
	<iframe style="margin-left: 100px;" src="https://www.google.com/maps/embed?pb=!1m25!1m12!1m3!1d50413.21012177806!2d-122.52714740911674!3d37.840967739790244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m10!3e6!4m4!2s37.876152%2C+-122.511077!3m2!1d37.876152!2d-122.511077!4m3!3m2!1d37.80531!2d-122.473599!5e0!3m2!1sen!2sus!4v1465103128538" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
	<br>
	<a style="margin-left: 100px;" href="https://goo.gl/maps/kH83gPmGLyQ2">Google Maps Link</a>
	<br>
	<div id="sb_chart">
	    <div id="sb_chart_div"></div>
	    <div id="sb_control_div"></div>
	</div>
	<div style="margin-left: 100px;">
		<input type="button" id="sb_edit" value="Edit Chart" />
		<br>
		<p><?php echo "maps api url string: ".$sb_maps_api_url; ?></p>
		<p><?php echo "google maps api status: ".$sb_json_a["status"]; ?></p>
		<p><?php echo "distance: ".$sb_distance; ?></p>
		<p><?php echo "duration: ".$sb_dur_txt; ?></p>
		<p><?php echo "duration_value: ".$sb_dur_val; ?></p>
		<p><?php echo "duration_in_traffic: <span style='color: ".$css_text_color.";'>".$sb_dur_traffic_txt; ?></span></p> 
		<p><?php echo "duration_in_traffic_value: ".$sb_dur_traffic_val; ?></p>
	</div>
</div>

<div style="width: 800px; float: left; margin-right: 10px; word-wrap: break-word;">
	<h3 style="margin-left: 100px;" >North Bound Traffic</h3>
	<iframe style="margin-left: 100px;"
	src="https://www.google.com/maps/embed?pb=!1m25!1m12!1m3!1d50406.04342351291!2d-122.51691005905764!3d37.85145163930396!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m10!3e6!4m4!2s37.805472%2C+-122.473531!3m2!1d37.805472!2d-122.473531!4m3!3m2!1d37.884882999999995!2d-122.516651!5e0!3m2!1sen!2sus!4v1465103325431" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
	<br>
	<a style="margin-left: 100px;" href="https://goo.gl/maps/Hp2UF7VZaBk">Google Maps Link</a>
	<br>
	<div id="nb_chart">
	    <div id="nb_chart_div"></div>
	    <div id="nb_control_div"></div>
	</div>
	<div style="margin-left: 100px;">
		<input type="button" id="nb_edit" value="Edit Chart" />
		<br>
		<p><?php echo "maps api url string: ".$nb_maps_api_url; ?></p>
		<p><?php echo "google maps api status: ".$nb_json_a["status"]; ?></p>
		<p><?php echo "distance: ".$nb_distance; ?></p>
		<p><?php echo "duration: ".$nb_dur_txt; ?></p>
		<p><?php echo "duration_value: ".$nb_dur_val; ?></p>
		<p><?php echo "duration_in_traffic: <span style='color: ".$css_text_color.";'>".$nb_dur_traffic_txt; ?></span></p> 
		<p><?php echo "duration_in_traffic_value: ".$nb_dur_traffic_val; ?></p>
	</div>
</div>
<div style="width: 100%; clear: left;">&nbsp;</div>

<?php

?>
<div style="margin-left: 100px;">
	<h3>data.sparkfun.com stream</h3>
	<a href="https://data.sparkfun.com/streams/YGoyGv65m0Cm51aAxOK7">data stream link</a>
	<p><?php echo "datalog url string: ".$datalogurl; ?></p>
	<p><?php echo "datalog return: ".$datalogurl_return; ?></p>
	<h3>SB maps api return</h3>
	<pre><?php echo $sb_returned_content; ?></pre>
	<h3>NB maps api return</h3>
	<pre><?php echo $nb_returned_content; ?></pre>

</div>

<script type="text/javascript">
(function countdown(remaining) {
    if(remaining === 0)
        location.reload(true);
    document.getElementById('countdown').innerHTML = remaining;
    setTimeout(function(){ countdown(remaining - 1); }, 1000);
})(180);
</script>



<script type="text/javascript">
	google.load('visualization', '1', {packages: ['controls', 'charteditor']});
	google.setOnLoadCallback(drawChart);

function drawChart() {
	var public_key = 'YGoyGv65m0Cm51aAxOK7';
// DRAW SB CHART
	// JSONP request
    var jsonData = $.ajax({
      url: 'https://data.sparkfun.com/output/' + public_key + '.json',
      // data: {page: 1},
      dataType: 'jsonp',
    }).done(function (results) {

    var sb_data = new google.visualization.DataTable();
    sb_data.addColumn('datetime', 'Time');
    sb_data.addColumn('number', 'value traffic');
    sb_data.addColumn('number', 'value normal');
    sb_data.addColumn('number', 'minutes traffic');
    sb_data.addColumn('number', 'minutes normal');

    $.each(results, function (i, row) {
            sb_data.addRow([
              (new Date(row.timestamp)),
              parseFloat(row.sb_dur_traffic_val),
              parseFloat(row.sb_dur_val),
              parseFloat(row.sb_dur_traffic_txt),
              parseFloat(row.sb_dur_txt)
            ]);
          });
    
    var sb_dash = new google.visualization.Dashboard(document.getElementById('sb_chart'));

    var sb_control = new google.visualization.ControlWrapper({
        controlType: 'ChartRangeFilter',
        containerId: 'sb_control_div',
        options: {
            filterColumnIndex: 0,
            ui: {
                chartOptions: {
                    height: 50,
                    width: 800,
                    series: {
			            0: { color: '#ff0000' },
			            1: { color: '#00b300' },
			            1: { color: '#ff00ff' },
			            1: { color: '#00ff00' }
			          },
                    chartArea: {
                        width: '75%'
                    }
                },
                chartView: {
                    columns: [0, 1]
                }
            }
        }
    });

    var sb_chart = new google.visualization.ChartWrapper({
        chartType: 'LineChart',
        containerId: 'sb_chart_div'
    });

    function setOptions (sb_wrapper) {
        // sets the options on the chart wrapper so that it draws correctly
        sb_wrapper.setOption('title', 'SB Traffic Log');
        sb_wrapper.setOption('curveType', 'function');
        sb_wrapper.setOption('height', 400);
        sb_wrapper.setOption('width', 800);
        // wrapper.setOption('series', { 0: { color: '#ff0000' }, 1: { color: '#00b300' } });
        sb_wrapper.setOption('series', { 0: { targetAxisIndex: 0, color: '#ff00ff' }, 1: { targetAxisIndex: 0, color: '#00b300' }, 2: { targetAxisIndex: 1, color: '#ff0000' }, 3: { targetAxisIndex: 1, color: '#00ff00' } });
        sb_wrapper.setOption('vAxes', { 0: { title: 'Duration Value' }, 1: { title: 'Duration Minutes' } });
        sb_wrapper.setOption('chartArea.width', '75%');
        // the chart editor automatically enables animations, which doesn't look right with the ChartRangeFilter
        sb_wrapper.setOption('animation.duration', 0);
    }
    
    setOptions(sb_chart);
    
    document.getElementById('sb_edit').onclick = function () {
        var sb_editor = new google.visualization.ChartEditor();
        google.visualization.events.addListener(sb_editor, 'ok', function () {
            sb_chart = sb_editor.getChartWrapper();
            setOptions(sb_chart);
            sb_dash.bind([sb_control], [sb_chart]);
            sb_dash.draw(sb_data);
        });
        sb_editor.openDialog(sb_chart);
    };
    
    sb_dash.bind([sb_control], [sb_chart]);
    sb_dash.draw(sb_data);

	});

// DRAW NB CHART
	// JSONP request
    var jsonData = $.ajax({
      url: 'https://data.sparkfun.com/output/' + public_key + '.json',
      // data: {page: 1},
      dataType: 'jsonp',
    }).done(function (results) {

    var nb_data = new google.visualization.DataTable();
    nb_data.addColumn('datetime', 'Time');
    nb_data.addColumn('number', 'value traffic');
    nb_data.addColumn('number', 'value normal');
    nb_data.addColumn('number', 'minutes traffic');
    nb_data.addColumn('number', 'minutes normal');

    $.each(results, function (i, row) {
            nb_data.addRow([
              (new Date(row.timestamp)),
              parseFloat(row.nb_dur_traffic_val),
              parseFloat(row.nb_dur_val),
              parseFloat(row.nb_dur_traffic_txt),
              parseFloat(row.nb_dur_txt)
            ]);
          });
    
    var nb_dash = new google.visualization.Dashboard(document.getElementById('nb_chart'));

    var nb_control = new google.visualization.ControlWrapper({
        controlType: 'ChartRangeFilter',
        containerId: 'nb_control_div',
        options: {
            filterColumnIndex: 0,
            ui: {
                chartOptions: {
                    height: 50,
                    width: 800,
                    series: {
			            0: { color: '#ff0000' },
			            1: { color: '#00b300' },
			            1: { color: '#ff00ff' },
			            1: { color: '#00ff00' }
			          },
                    chartArea: {
                        width: '75%'
                    }
                },
                chartView: {
                    columns: [0, 1]
                }
            }
        }
    });

    var nb_chart = new google.visualization.ChartWrapper({
        chartType: 'LineChart',
        containerId: 'nb_chart_div'
    });

    function setOptions (nb_wrapper) {
        // sets the options on the chart wrapper so that it draws correctly
        nb_wrapper.setOption('title', 'NB Traffic Log');
        nb_wrapper.setOption('curveType', 'function');
        nb_wrapper.setOption('height', 400);
        nb_wrapper.setOption('width', 800);
        // wrapper.setOption('series', { 0: { color: '#ff0000' }, 1: { color: '#00b300' } });
        nb_wrapper.setOption('series', { 0: { targetAxisIndex: 0, color: '#ff00ff' }, 1: { targetAxisIndex: 0, color: '#00b300' }, 2: { targetAxisIndex: 1, color: '#ff0000' }, 3: { targetAxisIndex: 1, color: '#00ff00' } });
        nb_wrapper.setOption('vAxes', { 0: { title: 'Duration Value' }, 1: { title: 'Duration Minutes' } });
        nb_wrapper.setOption('chartArea.width', '75%');
        // the chart editor automatically enables animations, which doesn't look right with the ChartRangeFilter
        nb_wrapper.setOption('animation.duration', 0);
    }
    
    setOptions(nb_chart);
    
    document.getElementById('nb_edit').onclick = function () {
        var nb_editor = new google.visualization.ChartEditor();
        google.visualization.events.addListener(nb_editor, 'ok', function () {
            nb_chart = nb_editor.getChartWrapper();
            setOptions(nb_chart);
            nb_dash.bind([nb_control], [nb_chart]);
            nb_dash.draw(nb_data);
        });
        nb_editor.openDialog(nb_chart);
    };
    
    nb_dash.bind([nb_control], [nb_chart]);
    nb_dash.draw(nb_data);

	});
}
</script>
</body>
</html>
