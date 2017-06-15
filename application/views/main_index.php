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
        google.charts.setOnLoadCallback(drawActivities);
        google.charts.setOnLoadCallback(drawTrainings);

        function drawActivities() {
            var jsonData = $.ajax({
                url: "<?php echo base_url() . 'index.php/main/get_activities' ?>",
                dataType: "json",
                async: false
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('activities_div'));
            chart.draw(data, {width: 400, height: 220});
        }

        function drawTrainings() {
            var jsonData = $.ajax({
                url: "<?php echo base_url() . 'index.php/main/get_trainings' ?>",
                dataType: "json",
                async: false
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            var chart = new google.visualization.ColumnChart(document.getElementById('training_div'));
            chart.draw(data, {width: 350, height: 220});
        }

        function need_password(){
            if (prompt('Key-in password before proceeding.') == 'fb888'){
                return true;
            }
            return false;
        }
    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-6">
        <fieldset>
            <legend>General Options:</legend>
            <a href="<?= base_url();?>index.php/personnel" onclick="return need_password();" class="btn btn-success"><span class="glyphicon glyphicon-lock"></span> Personnel</a>
            <a href="<?= base_url();?>index.php/duty/schedule" onclick="return need_password();" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> Plot Duty Schedule</a>
            <a href="<?= base_url();?>index.php/fire/data" class="btn btn-danger"><span class="glyphicon glyphicon-fire"></span> Fire Data</a>
        </fieldset>
        <div>&nbsp;</div><fieldset>
            <legend>Log Attendance:</legend>
            <a href="<?= base_url();?>index.php/duty/attendance" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Log Duties</a>
            <a href="<?= base_url();?>index.php/fire/attendance" class="btn btn-warning"><span class="glyphicon glyphicon-fire"></span> Log Fire Responses</a>
            <a href="<?= base_url();?>index.php/training/attendance" class="btn btn-success"><span class="glyphicon glyphicon-flag"></span> Log Training</a>
        </fieldset>
        <div>&nbsp;</div>
            <a href="<?= base_url();?>index.php/special/attendance" class="btn btn-info"><span class="glyphicon glyphicon-star"></span> Log Special Activity</a>
            <a href="<?= base_url();?>index.php/meeting/attendance" class="btn btn-primary"><span class="glyphicon glyphicon-book"></span> Log GM Meeting</a>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </div>
    <div class="col-lg-6">
        <fieldset>
            <legend>Datas:</legend>
            <a href="<?= base_url();?>index.php/duty/review" class="btn btn-primary"><span class="glyphicon glyphicon-list"></span> Duties Data</a>
            <a href="<?= base_url();?>index.php/duty/unit_review" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Duty per Unit</a>
            <a href="<?= base_url();?>index.php/fire/lists" class="btn btn-danger"><span class="glyphicon glyphicon-list"></span> Fire Alarms</a>
            <a href="<?= base_url();?>index.php/training/lists" class="btn btn-success"><span class="glyphicon glyphicon-list"></span> Training Data</a>
<!--            <a href="--><?//= base_url();?><!--index.php/fire/review_attendance" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Fire Attendance</a>-->
        </fieldset>
        <div>&nbsp;</div>
            <a href="<?= base_url();?>index.php/special/lists" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Special Activity Data</a>
            <a href="<?= base_url();?>index.php/meeting/lists" class="btn btn-primary"><span class="glyphicon glyphicon-list"></span> Meeting Data</a>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </div>
    <div class="col-lg-6">
        <fieldset>
            <legend>Reports:</legend>
            <a href="<?= base_url();?>index.php/main/monthly_reports" class="btn btn-info"><span class="glyphicon glyphicon-file"></span> Monthly Report Table</a>
            <a href="<?= base_url();?>index.php/main/top_reports" class="btn btn-info"><span class="glyphicon glyphicon-th-large"></span> Show Top Reports</a>
        </fieldset>
    </div>
</div>
<div>&nbsp;</div>
<div class="container">
    <div class="col-lg-4">
        <u>Activities <?=$current_year;?></u>
        <div id="activities_div">Loading...</div>
    </div>
    <div class="col-lg-4">
        <u>Training Attendance of last 6 months</u>
        <div id="training_div">Loading...</div>
    </div>
    <div class="col-lg-4">
        <u>Datum</u>
        <div style="margin-left:20px;"><span style="color: green;">Active Members: <?=$active_members;?></span></div>
        <div style="margin-left:20px;"><span style="color: red;">Inactive Members: <?=$inactive_members;?></span></div>
        <div>&nbsp;</div>
        <div style="margin-left:20px;">Duty Efficiency in <?=date('F');?>: <?=$duty_month;?></div>
        <div style="margin-left:20px;">Previous month : <?=$duty_previous_month;?></div>
        <div>&nbsp;</div>
        <div style="margin-left:20px;"><span style="color: green;"><?=date('Y')?> Highest Training attendance : <?php if (isset($highest_training[0]->total)) echo $highest_training[0]->total; else echo '--';?></span></div>
        <div style="margin-left:20px;"><span style="color: red;">Lowest : <?php if (isset($lowest_training[0]->total)) echo $lowest_training[0]->total; else echo '--';?></span></div>
    </div>
</div>
<div style="clear: both">&nbsp;</div>
<div style="text-align: center;font-size:10px;">All rights reserved. Copyright &copy; Jeremy Ling &nbsp;</div>
</body>
</html>