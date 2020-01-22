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

        function showScheduleVersion(){
            document.getElementById("schedule_form").submit();
        }
    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-6">
        <fieldset>
            <legend><span class="glyphicon glyphicon-calendar"></span> Duty Scheduling</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/duty/schedule' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Select Day:*</label>
                    <div class="col-lg-5">
                        <select class="form-control" name="schedule" id="schedule">
                            <option value="Monday" <?php echo ($select_schedule == "Monday")? 'selected':'';?>>Monday</option>
                            <option value="Tuesday" <?php echo ($select_schedule == "Tuesday")? 'selected':'';?>>Tuesday</option>
                            <option value="Wednesday" <?php echo ($select_schedule == "Wednesday")? 'selected':'';?>>Wednesday</option>
                            <option value="Thursday" <?php echo ($select_schedule == "Thursday")? 'selected':'';?>>Thursday</option>
                            <option value="Friday" <?php echo ($select_schedule == "Friday")? 'selected':'';?>>Friday</option>
                            <option value="Saturday" <?php echo ($select_schedule == "Saturday")? 'selected':'';?>>Saturday</option>
                            <option value="Sunday" <?php echo ($select_schedule == "Sunday")? 'selected':'';?>>Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Effective: *</label>
                    <div class="col-lg-4">
                        <select class="form-control" name="select_duty_version" id="select_duty_version">
                            <option value="">--Select--</option>
                            <?php foreach ($version as $key => $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo ($select_duty_version == $value->id)? 'selected':'';?>><?=$value->name;?></option>
                            <?php }?>
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
                        <a href="<?= base_url();?>index.php/duty/duty_schedule" class="btn btn-primary" >Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
    <div class="col-lg-5">
        <fieldset>
            <legend>Schedule Overview</legend>
            <form id="schedule_form"  action='<?= base_url();?>index.php/duty/schedule' method="post">
            Show as: <select class="form-control" name="duty_version" id="duty_version" onchange="showScheduleVersion()" >
                <option value="">--Select--</option>
                <?php foreach ($version as $key => $value) { ?>
                    <option value="<?=$value->id;?>" <?php echo ($selected_version == $value->id)? 'selected':'';?>><?=$value->name;?></option>
                <?php }?>
            </select>
            <div>&nbsp;</div>
            </form>
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
                        <?php foreach ($Monday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Tuesday)) {?><td>
                        <?php foreach ($Tuesday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Wednesday)) {?><td>
                        <?php foreach ($Wednesday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Thursday)) {?><td>
                        <?php foreach ($Thursday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Friday)) {?><td>
                        <?php foreach ($Friday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Saturday)) {?><td>
                        <?php foreach ($Saturday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    <?php if (isset($Sunday)) {?><td>
                        <?php foreach ($Sunday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_schedule/'.$value->unit.'/'.$value->schedule.'/'.$value->version.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                    </td><?php } ?>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>