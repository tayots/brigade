<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Year', 'Year', 'Count'],
                <?php
                foreach ($chart_data as $data) {
                    echo '[' . $data->year . ',' . $data->unit . ',' . $data->counter . '],';
                }
                ?>
            ]);

            var options = {
                chart: {
                    title: 'Attendance'
                }
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, {width: 400, height: 240});
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Attendance Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend>Reports</legend>
            <div id="chart_div" style="width: 900px; height: 300px;"></div>
            <a href="<?= base_url();?>index.php/main/add" class="btn btn-success">Add Attendance</a>
            <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
        </fieldset>
    </div>
</div>
</body>
</html>