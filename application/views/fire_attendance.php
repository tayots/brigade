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
            <legend style="color: #d58512"><span class="glyphicon glyphicon-user"></span> Add unit on Fire Responses</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/fire_attendance' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Select 10-70 last 30 days:*</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="title" id="title">
                            <option value="">--Select--</option>
                            <?php foreach ($fire_list as $key => $value) { ?>
                                <option value="<?=$value->id;?>|<?=$value->date_of_fire;?>" <?php echo isset($selected_title)? 'selected':'';?>><?=$value->date_of_fire;?> | <?=$value->location;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit No.*</label>
                    <div class="col-lg-3">
                        <input style="height: 100px;width: 150px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit" id="unit" min="1" maxlength="10" autofocus>
                    </div>
                    <div class="col-lg-5" style="text-align: right;">
                        <button type="submit" class="btn btn-success" >Save</button>
                        <a href="<?= base_url();?>index.php/main/add" class="btn btn-primary" >Clear</a>
                        <a href="<?= base_url();?>index.php/main/fire_data" class="btn btn-danger" > <span class="glyphicon glyphicon-fire"></span> Fire Data</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
                <div class="form-group">
                </div>
            </form>
            History: <a href="<?= base_url();?>index.php/main/fire_review_attendance" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Go to Review Attendance</a>
            <?php if (count($information) > 0) {?>
            <div style="overflow-y: scroll; height: 250px;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                        <?php if (isset($information)) {  $total = 0;?>
                            <?php foreach ($information as $key => $value) { $total += 1;?>
                            <tr>
                                <td><?php echo $value->attendance_date; ?></td>
                                <td style="text-align: center;"><?php echo $value->unit; ?></td>
                                <td><?php echo $value->location; ?></td>
                                <td><a href="<?= base_url();?>index.php/main/delete_unit/<?php echo $value->id; ?>/<?php echo $value->attendance_date;?>/add">Delete</a></td>
                            </tr>
                            <?php } ?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div style="font-size: 20px;">Total: <strong><?=$total;?></strong></div>
            <?php }?>
        </fieldset>
    </div>
</div>
</body>
</html>