<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="<?= base_url();?>bootstrap/js/google_loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages':['corechart','bar']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawFireRespond);
        google.charts.setOnLoadCallback(drawWaterUsed);
        google.charts.setOnLoadCallback(drawNoUsed);
        google.charts.setOnLoadCallback(drawFires);

        function drawFireRespond() {
            var select_from = $('#select_from').val();
            var select_to = $('#select_to').val();
            var jsonData = $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'index.php/main/get_fire_respond' ?>",
                dataType: "json",
                async: false,
                data: {
                    'select_from': select_from,
                    'select_to': select_to
                }
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('fire_respond_div'));
            chart.draw(data, {width: 380, height: 200, pieHole: 0.1});
        }

        function drawWaterUsed() {
            var select_from = $('#select_from').val();
            var select_to = $('#select_to').val();
            var jsonData = $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'index.php/main/get_water_used' ?>",
                dataType: "json",
                async: false,
                data: {
                    'select_from': select_from,
                    'select_to': select_to
                }
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            var chart = new google.visualization.ColumnChart(document.getElementById('water_used_div'));
            chart.draw(data, {width: 1000, height: 220});
        }

        function drawNoUsed() {
            var select_from = $('#select_from').val();
            var select_to = $('#select_to').val();
            var jsonData = $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'index.php/main/get_no_used' ?>",
                dataType: "json",
                async: false,
                data: {
                    'select_from': select_from,
                    'select_to': select_to
                }
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            var chart = new google.visualization.ColumnChart(document.getElementById('no_used_div'));
            chart.draw(data, {width: 700, height: 200, colors: ['purple']});
        }

        function drawFires() {
            var select_from = $('#select_from').val();
            var select_to = $('#select_to').val();
            var jsonData = $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'index.php/main/get_fires' ?>",
                dataType: "json",
                async: false,
                data: {
                    'select_from': select_from,
                    'select_to': select_to
                }
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.arrayToDataTable(JSON.parse(jsonData));

            var chart = new google.visualization.LineChart(document.getElementById('fires_div'));
            chart.draw(data, {width: 700, height: 200, colors: ['red','blue']});
        }
    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-11">
        <fieldset>
            <legend>Fire Reports Options</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/fire_reports' method="post">
                <div class="form-group">
                    <div class="bs-example" data-example-id="single-button-dropdown">
                        <label for="title" class="control-label">From:*</label>
                        <div class="btn-group">
                            <input type="date" class="form-control" name="select_from" id="select_from" value="<?php echo $selected_from;?>" >
                        </div>
                        <label for="title" class="control-label">To:*</label>
                        <div class="btn-group">
                            <input type="date" class="form-control" name="select_to" id="select_to" value="<?php echo $selected_to;?>">
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success" >Search</button>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main/fire_reports" class="btn btn-default" >Clear</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
                        <div class="btn-group" style="margin-left: 20px;">
                            Shown From <span style="color:red;font-size:16px;"><?=date('M d, Y',strtotime($selected_from)); ;?></span> -- <span style="color:red;font-size:16px;"><?=date('M d, Y',strtotime($selected_to)); ;?></span>
                        </div>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            <div class="form-group">
                <div class="col-lg-4">
                    <strong>Fire Responses:</strong> <?=$total_fires;?>
                    <div id="fire_respond_div">Loading...</div>
                </div>
                <div class="col-lg-8">
                    <strong>Monthly Fires:</strong>
                    <div id="fires_div">Loading...</div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4">
                    <strong>Summary:</strong>
                    <div>
                        <table>
                            <tr>
                                <td width="200px">Total Fires Recorded:</td>
                                <td><?=$total_fires;?></td>
                            </tr>
                            <tr>
                                <td>Responded / Non-respond:</td>
                                <td><?=$responded;?> / <?=$no_respond;?></td>
                            </tr>
                            <tr>
                                <td>Total Water Used:</td>
                                <td><?=$total_water;?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Fire Occurs DAWN to NN:</td>
                                <td style="color:blue;"><?=$am_fires;?>%</td>
                            </tr>
                            <tr>
                                <td>Fire Occurs NN to MN:</td>
                                <td style="color:orange;"><?=$pm_fires;?>%</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-top:10px;">
                    <strong>Responded but no water used:</strong>
                    <div id="no_used_div">Loading...</div>
                </div>
                <div class="col-lg-12">
                    <strong>Water Used Vs. Fire Responded:</strong>
                    <div id="water_used_div">Loading...</div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
</body>
</html>