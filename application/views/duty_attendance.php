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

    </script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend style="color: #337ab7;"><span class="glyphicon glyphicon-user"></span> Log Duties</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/duty/save' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Date Duty:*</label>
                    <div class="col-lg-4">
                        <input type="date" class="form-control" name="date_of_duty" id="date_of_duty" value="<?php if ($this->input->post('date_of_duty')) { echo $this->input->post('date_of_duty');} else { echo $current_date; }?>" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-3 control-label">Time-In:*</label>
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
                    <label for="lastName" class="col-lg-2 control-label">Time-Out:*</label>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_c_min');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_hour" id="time_c_hour" min="0" maxlength="2" value="<?=$this->input->post('time_c_hour');?>">
                    </div>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_c_period');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_min" id="time_c_min" min="0   " maxlength="2" value="<?=$this->input->post('time_c_hour');?>">
                    </div>
                    <div class="col-lg-1">
                        <select class="form-control" name="time_c_period" id="time_c_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" selected>AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit No.*</label>
                    <div class="col-lg-3">
                        <input style="height: 100px;width: 150px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit" id="unit" min="1" maxlength="3" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3">&nbsp;</div>
                    <div class="col-lg-3" style="text-align: right;">
                        <button type="submit" class="btn btn-success" >Save</button>
                        <a href="<?= base_url();?>index.php/duty/attendance" class="btn btn-primary" >Clear</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</div>
</body>
</html>