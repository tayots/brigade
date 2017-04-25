<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Attendance Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend>Review Fire Response Attendance</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/fire_review_attendance' method="post">
                <div class="form-group">
                   <div class="col-lg-1">
                       <label for="title" class="control-label">Event:*</label>
                   </div>
                   <div class="col-lg-4">
                        <select name="title" id="title" class="form-control col-lg-2">
                            <option value="">--Select--</option>
                            <?php foreach ($title_list as $key => $value) { ?>
                                <option value="<?=$value->title;?>" <?php echo ($title == $value->title) ? 'selected':''?>><?=$value->attendance_date;?> > <?=$value->title;?></option>
                            <?php } ?>
                        </select>
                   </div>
                    <div class="col-lg-1">
                        <label for="title" class="control-label">Category:*</label>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control" name="category" id="category">
                            <option value="">--Select--</option>
                            <?php foreach ($category_list as $key => $value) { ?>
                                <option value="<?=$value->category;?>" <?php echo ($category == $value->category) ? 'selected':''?>><?=$value->category;?></option>
                            <?php } ?>
                        </select>
                    </div>
                   <div class="col-lg-3">
                        <button type="submit" class="btn btn-success" >Search</button>
                        <a href="<?= base_url();?>index.php/main/review_attendance" class="btn btn-primary" >Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                    </div>
                </div>
            </form>
            Results: <a href="<?= base_url();?>index.php/main/add" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Go to Add Attendance</a>
            <div style="overflow-y: scroll; height: 400px;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                        <?php if (isset($information)) {  $total = 0;?>
                            <?php foreach ($information as $key => $value) { $total += 1;?>
                            <tr>
                                <td><?php echo $value->attendance_date; ?></td>
                                <td style="text-align: center;"><?php echo $value->unit; ?></td>
                                <td><?php echo $value->category; ?></td>
                                <td><?php echo $value->title; ?></td>
                                <td><a href="<?= base_url();?>index.php/main/delete_unit/<?php echo $value->id; ?>/<?php echo $value->attendance_date;?>/review_attendance">Delete</a></td>
                            </tr>
                            <?php } ?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div style="font-size: 20px;">Total: <strong><?=$total;?></strong></div>
        </fieldset>
    </div>
</div>
</body>
</html>