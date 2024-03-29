<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">

        function validate_digit(el, nextFieldID) {
            if(el.value.length == 2){
                document.getElementById(nextFieldID).focus();
                document.getElementById(nextFieldID).select();
            }
        }

        function max_two_digit(el){
            if(el.value.length > 2){
                el.value = String(el.value[0])+String(el.value[1]);
            }
            else if (el.value.length < 2){
                el.value = '0'+String(el.value[0]);
            }
        }

        function mirror_me(el){
            document.getElementById('time_c_period').value = el.value;
        }

        function promptStatus(){
            if (confirm('Are you sure you want to clear?')){
                return true;
            }
            return false;
        }

    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-7">
        <fieldset>
            <legend style="color: #FF5733;"><span class="glyphicon glyphicon-user"></span> Log Cadet Attendance</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" id="duty_attendance_form" action='<?= base_url();?>index.php/duty/cadet_attendance' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-4 control-label">Attendance Date:*</label>
                    <div class="col-lg-5">
                        <input style="height: 60px;width:350px;font-size: 40px;" height="40px" type="date" class="form-control" name="date_of_duty" id="date_of_duty" value="<?php if ($this->input->post('date_of_duty')) { echo $this->input->post('date_of_duty');} else { echo $current_date; }?>" onchange="document.getElementById('duty_attendance_form').submit();">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-4 control-label">Use Schedule:*</label>
                    <div class="col-lg-5">
                        <select class="form-control" name="cadet_version" id="cadet_version" style="height: 50px;width:350px;font-size: 20px;">
                            <option value="">--Select--</option>
                            <?php foreach ($cadet_version as $key => $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo ($selected_version == $value->id)? 'selected':'';?>><?=$value->name;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label for="firstName" class="col-lg-4 control-label">Cadet Name:*</label>
                    <div class="col-lg-4">
                        <select class="form-control" name="unit" id="unit" style="height: 50px;width:350px;font-size: 20px;">
                            <option value="">--Select--</option>
                            <?php foreach ($cadet_name as $key => $value) { ?>
                                <option value="<?=$value->unit;?>" <?php echo ($selected_unit == $value->unit)? 'selected':'';?>><?=$value->unit.' - '.$value->first_name.' '.$value->last_name;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-4 control-label">Time-In:*</label>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_min');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_hour" id="time_r_hour" min="0" maxlength="2" value="<?=$this->input->post('time_r_hour');?>">
                    </div>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_period');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_min" id="time_r_min" min="0" maxlength="2" value="<?=$this->input->post('time_r_min');?>">
                    </div>
                    <div class="col-lg-1" style="margin-right:21px;">
                        <select onchange="mirror_me(this);" class="form-control" name="time_r_period" id="time_r_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" >AM</option>
                            <option value="PM" selected>PM</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-4 control-label">Time-Out:*</label>
                    <div class="col-lg-1">
                        <input onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_hour" id="time_c_hour" min="0" maxlength="2" value="05">
                    </div>
                    <div class="col-lg-1">
                        <input onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_min" id="time_c_min" min="0" maxlength="2" value="30">
                    </div>
                    <div class="col-lg-1">
                        <select class="form-control" name="time_c_period" id="time_c_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" selected>AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">&nbsp;</div>
                    <div class="col-lg-4" style="text-align: right;">
                        <button type="submit" class="btn btn-success" >Save</button>
                        <a href="<?= base_url();?>index.php/duty/cadet_attendance" class="btn btn-primary" onclick="return promptStatus();">Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
    <div class="col-lg-5">
        <fieldset>
            <legend>History:</legend>
            <table class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Unit</th>
                        <th>Date & Time</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($duties)){?>
                        <?php foreach($duties as $key => $value):?>
                        <tr>
                            <td><?=$key+1;?></td>
                            <td><?=$value->unit;?></td>
                            <td><?=date('M d, Y - l ',strtotime($value->attendance_date));?> <br>(<?=$value->time_in;?> - <?=$value->time_out;?>)</td>
                            <td><?=$value->remarks;?></td>
                        </tr>
                        <?php endforeach;?>
                    <?php } else {?>
                        <tr>
                            <td colspan="4">No record yet.</td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>