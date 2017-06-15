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
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-12">
        <fieldset>
            <legend>Top XX Reports by Category</legend>
            <form class="form-horizontal" role="form" id="special_review_form" action='<?= base_url();?>index.php/main/top_reports' method="post">
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
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Top (e.g 10, 20):</label>
                    </div>
                    <div class="col-lg-1">
                        <input type="text" class="form-control" name="top_limit" id="top_limit" value="<?=$top_limit;?>" placeholder="optional" autofocus>
                    </div>
                   <div class="col-lg-2">
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
                Top <?=$top_limit;?> Results From <strong><?=$from_date;?></strong> To <strong><?=$to_date;?></strong>
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
            <table style="font-size:14px;">
                <tr>
                    <td><strong>Summary:</strong></td>
                </tr>
                <tr>
                    <td width="150px">Fire Responses:</td>
                    <td><?=$fire_summary;?></td>
                </tr>
                <tr>
                    <td>Training:</td>
                    <td><?=$training_summary;?></td>
                </tr>
                <tr>
                    <td>Meeting:</td>
                    <td><?=$meeting_summary;?></td>
                </tr>
                <tr>
                    <td>Special Activity:</td>
                    <td><?=$special_summary;?></td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
</html>
