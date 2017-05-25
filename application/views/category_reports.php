<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-12">
        <fieldset>
            <legend>Category Reports</legend>
            <form class="form-horizontal" role="form" id="special_review_form" action='<?= base_url();?>index.php/main/category_reports' method="post">
                <div class="form-group">
                   <div class="col-lg-1">
                       <label for="title" class="control-label">From:*</label>
                   </div>
                   <div class="col-lg-2">
                       <input type="date" class="form-control" name="from_date" id="from_date" value="<?=$from_date;?>" autofocus>
                   </div>
                    <div class="col-lg-1">
                        <label for="title" class="control-label">To:*</label>
                    </div>
                    <div class="col-lg-2">
                        <input type="date" class="form-control" name="to_date" id="to_date" value="<?=$to_date;?>" autofocus>
                    </div>
                   <div class="col-lg-4">
                        <div class="bs-example" data-example-id="single-button-dropdown">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success" >Search</button>
                            </div>
                            <div class="btn-group">
                                <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            <div class="form-group">
                Top 20 Results From <strong><?=$from_date;?></strong> To <strong><?=$to_date;?></strong>
            </div>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size:15px;">
                <tbody>
                    <tr>
                        <td>Trainings</td>
                        <td>Fire Responses</td>
                        <td>Duties</td>
                        <td>Meetings</td>
                        <td>Special Activites</td>
                    </tr>
                    <tr>
                        <td>
                            <?php foreach($training as $value) {?>
                                <?=$value->unit;?> : <?=$value->total;?><br>
                            <?php }?>
                        </td>
                        <td>
                            <?php foreach($fire as $value) {?>
                                <?=$value->unit;?> : <?=$value->total;?><br>
                            <?php }?>
                        </td>
                        <td>
                            <?php foreach($duty as $value) {?>
                                <?=$value->unit;?> : <?=$value->total;?><br>
                            <?php }?>
                        </td>
                        <td>
                            <?php foreach($meeting as $value) {?>
                                <?=$value->unit;?> : <?=$value->total;?><br>
                            <?php }?>
                        </td>
                        <td>
                            <?php foreach($special as $value) {?>
                                <?=$value->unit;?> : <?=$value->total;?><br>
                            <?php }?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</html>
