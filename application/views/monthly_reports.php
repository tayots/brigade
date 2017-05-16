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
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend>Monthly Report Options</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/monthly_reports' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Month:*</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="month" class="form-control" name="attendance_month" id="attendance_month" value="<?php echo isset($attendance_month) ? $attendance_month:'';?>">
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-success" >Search</button>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            <div class="form-group">
                <div class="col-lg-7">
                    Subject: Summary of all Activities for the month of <span style="color:red;"><?=$selected_month;?></span>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size:14px;">
                <thead>
                    <tr>
                        <th>UNIT #</th>
                        <th>Duty</th>
                        <th>Fire Response</a> </th>
                        <th>Training</th>
                        <th>Meeting</th>
                        <th>Sp. Activity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($monthly)) {?>
                    <?php foreach ($monthly as $key => $value) { $total = 0;?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <?php  if (count($value) > 0) {?>
                                <?php foreach ($value as $m => $n) {?>
                                <td><?=$n->duty; $total += $n->duty; ?></td>
                                <td><?=$n->fire; $total += $n->fire; ?></td>
                                <td><?=$n->training; $total += $n->training; ?></td>
                                <td><?=$n->meeting; $total += $n->meeting; ?></td>
                                <td><?=$n->special; $total += $n->special; ?></td>
                                <td><?=$total;?></td>
                                <?php } ?>
                            <?php } else { $total +=0; ?>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td><?=$total;?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php }?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>