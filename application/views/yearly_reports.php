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
            <legend>Yearly Report Options</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/yearly_reports' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Year:*</label>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control" name="attendance_year" id="attendance_year">
                            <option value="2015" <?php echo ($attendance_year == 2015) ? 'selected':'';?>>2015</option>
                            <option value="2016" <?php echo ($attendance_year == 2016) ? 'selected':'';?>>2016</option>
                            <option value="2017" <?php echo ($attendance_year == 2017) ? 'selected':'';?>>2017</option>
                        </select>
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
                    <label>Subject: Summary of all Activities for the year of <?=$selected_year;?></label>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size:11px;">
                <thead>
                    <tr>
                        <th>UNIT #</th>
                        <th>Duty</th>
                        <th>Fire Response</th>
                        <th>Training</th>
                        <th>Meeting</th>
                        <th>Sp. Activity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($yearly)) {  $total = 0;?>
                    <?php foreach ($yearly as $key => $value) { $total += 1;?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <?php foreach ($value as $m => $n) {?>
                            <td><?php echo $n; ?></td>
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