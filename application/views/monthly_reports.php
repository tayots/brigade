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
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-11">
        <fieldset>
            <legend>Monthly Reports Options</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/monthly_reports' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Unit:*</label>
                    </div>
                    <div class="col-lg-2">
                        <select class="form-control" name="unit" id="unit" >
                            <option value="all">All</option>
                            <?php foreach ($unit_list as $key => $value) { ?>
                                <option value="<?=$value->unit;?>" <?php echo ($selected_unit == $value->unit)? 'selected':'';?>><?=$value->unit.' '.$value->first_name[0].$value->last_name[0];?></option>
                            <?php }?>
                        </select>
                    </div>
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
                            <a href="<?= base_url();?>index.php/main/monthly_reports" class="btn btn-default" >Clear</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            <div class="form-group">
                <div class="col-lg-7">
                    Subject: Summary of all Activities From <span style="color:red;"><?=$selected_from;?></span> To <span style="color:red;"><?=$selected_to;?></span>
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
                <?php if (isset($monthly)) {
                    $t_total = 0;
                    $t_duty = 0;
                    $t_fire = 0;
                    $t_training = 0;
                    $t_meeting = 0;
                    $t_special = 0;?>
                    <?php foreach ($monthly as $key => $n) { $total = 0;?>
                        <tr>
                            <td><?php echo $n->unit; ?></td>
                            <?php  if (count($n) > 0) {?>
                                <td><?=$n->duty; $total += $n->duty; $t_duty += $n->duty; ?></td>
                                <td><?=$n->fire; $total += $n->fire; $t_fire += $n->fire; ?></td>
                                <td><?=$n->training; $total += $n->training; $t_training += $n->training; ?></td>
                                <td><?=$n->meeting; $total += $n->meeting; $t_meeting += $n->meeting; ?></td>
                                <td><?=$n->special; $total += $n->special; $t_special += $n->special; ?></td>
                                <td><?=$total; $t_total+=$total;?></td>
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
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?=$t_duty;?></strong></td>
                    <td><strong><?=$t_fire;?></strong></td>
                    <td><strong><?=$t_training;?></strong></td>
                    <td><strong><?=$t_meeting;?></strong></td>
                    <td><strong><?=$t_special;?></strong></td>
                    <td><strong><?=$t_total;?></strong></td>
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
</body>
</html>