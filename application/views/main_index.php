<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
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
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-6">
        <fieldset>
            <legend>General Options:</legend>
            <a href="<?= base_url();?>index.php/main/personnel" onclick="return need_password();" class="btn btn-success"><span class="glyphicon glyphicon-lock"></span> Personnel</a>
            <a href="<?= base_url();?>index.php/main/duty_schedule" onclick="return need_password();" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> Duty Schedule</a>
        </fieldset>
        <div>&nbsp;</div><fieldset>
            <legend>Attendance Options:</legend>
            <a href="<?= base_url();?>index.php/duty/attendance" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Duty</a>
        </fieldset>
        <div>&nbsp;</div>
        <fieldset>
            <legend>Fire Response Options</legend>
            <a href="<?= base_url();?>index.php/main/fire_data" class="btn btn-danger"><span class="glyphicon glyphicon-fire"></span> Fire Data</a>
            <a href="<?= base_url();?>index.php/main/fire_attendance" class="btn btn-warning"><span class="glyphicon glyphicon-user"></span> Fire Attendance</a>
            <a href="<?= base_url();?>index.php/main/fire_review_attendance" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Review Attendance</a>
        </fieldset>
        <div>&nbsp;</div>
    </div>
    <div class="col-lg-6">
        <fieldset>
            <legend>Reports</legend>
            <a href="<?= base_url();?>index.php/main/monthly_reports" class="btn btn-primary"><span class="glyphicon glyphicon-tag"></span> Monthly Report Table</a>
            <a href="<?= base_url();?>index.php/main/yearly_reports" class="btn btn-primary"><span class="glyphicon glyphicon-tags"></span> Yearly Report Table</a>
        </fieldset>
    </div>
</div>
</body>
</html>