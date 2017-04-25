<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function beforeDelete() {
            if (confirm('Are you sure you want to delete this?\nYou can no longer associate it with reports.')) {
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
            <legend><span class="glyphicon glyphicon-calendar"></span> Duty Scheduling</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/duty_schedule' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Select Day:*</label>
                    <div class="col-lg-5">
                        <select class="form-control" name="schedule" id="schedule">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit No.*</label>
                    <div class="col-lg-3">
                        <input style="height: 100px;width: 150px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit" id="unit" min="1" maxlength="10" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-success" >Save</button>
                        <a href="<?= base_url();?>index.php/main/duty_schedule" class="btn btn-primary" >Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
    <div class="col-lg-5">
        <fieldset>
            <legend>Overview</legend>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <?php if (isset($Monday)) {?><td>
                        <?php foreach ($Monday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Tuesday)) {?><td>
                        <?php foreach ($Tuesday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Wednesday)) {?><td>
                        <?php foreach ($Wednesday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Thursday)) {?><td>
                        <?php foreach ($Thursday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Friday)) {?><td>
                        <?php foreach ($Friday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Saturday)) {?><td>
                        <?php foreach ($Saturday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Sunday)) {?><td>
                        <?php foreach ($Sunday as $key => $value) { echo '<a href="'.base_url().'index.php/main/delete_duty_schedule/'.$value->unit.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>