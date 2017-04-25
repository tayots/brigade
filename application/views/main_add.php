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
            <legend>Fire Response, Special Activity, Training, & Duty</legend>
            <?php if(isset($message)){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?=$alert_type;?>"><?=$message?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/save' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Title/Event*</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" name="title" id="title" value="<?php echo isset($title) ? $title:'';?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-3 control-label">Category*</label>
                    <div class="col-lg-3">
                        <select class="form-control" name="category" id="category">
                            <option value="">--Select--</option>
                            <?php if (!isset($category)) {?>
                                <option value="Fire Response" selected>Fire Response</option>
                                <option value="Training">Training</option>
                                <option value="Special Activity">Special Activity</option>
                                <option value="Duty">Duty</option>
                                <option value="Meeting">Meeting</option>
                            <?php } else {?>
                                <option value="Fire Response" <?php echo ($category == 'Fire Response') ? 'selected':'';?>>Fire Response</option>
                                <option value="Training" <?php echo ($category == 'Training') ? 'selected':'';?>>Training</option>
                                <option value="Special Activity" <?php echo ($category == 'Special Activity') ? 'selected':'';?>>Special Activity</option>
                                <option value="Duty" <?php echo ($category == 'Duty') ? 'selected':'';?>>Duty</option>
                                <option value="Meeting" <?php echo ($category == 'Meeting') ? 'selected':'';?>>Meeting</option>
                            <?php } ?>
                        </select>
                    </div>
                    <label for="lastName" class="col-lg-2 control-label">Date attended*</label>
                    <div class="col-lg-2">
                        <input type="text" class="form-control" name="attendance_date" id="attendance_date" placeholder="yyyy-mm-dd" maxlength="10" value="<?php echo isset($attendance_date) ? $attendance_date:'';?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit No.*</label>
                    <div class="col-lg-3">
                        <input style="height: 100px;width: 150px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit_number" id="unit_number" min="1" maxlength="10" autofocus>
                    </div>
                    <div class="col-lg-4" style="text-align: right;">
                        <button type="submit" class="btn btn-success" style="height: 100px;width:100px;">Save</button>
                        <a href="<?= base_url();?>index.php/main/add" class="btn btn-primary" style="height: 100px;">Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info" style="height: 100px;">Home</a>
                    </div>
                </div>
                <div class="form-group">
                </div>
            </form>
            History: <a href="<?= base_url();?>index.php/main/review_attendance" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Go to Review Attendance</a>
            <div style="overflow-y: scroll; height: 150px;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                        <?php if (isset($information)) {  $total = 0;?>
                            <?php foreach ($information as $key => $value) { $total += 1;?>
                            <tr>
                                <td><?php echo $value->attendance_date; ?></td>
                                <td style="text-align: center;"><?php echo $value->unit; ?></td>
                                <td><?php echo $value->category; ?></td>
                                <td><?php echo $value->title; ?></td>
                                <td><a href="<?= base_url();?>index.php/main/delete_unit/<?php echo $value->id; ?>/<?php echo $value->attendance_date;?>/add">Delete</a></td>
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