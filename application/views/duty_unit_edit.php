<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<form class="form-horizontal" id="update_duty_attendance" role="form" action='<?=base_url();?>index.php/duty/update_duty_attendance' method="post">
<div class="container">
    <div class="col-lg-9">
        <fieldset>
            <legend style="padding-top:10px;color: blue"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  Duty Details</legend>
            <?php if ($this->session->flashdata('message2')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type2'); ?>"><?php echo $this->session->flashdata('message2'); ?></div></div>
                </div>
            <?php }?>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Date Duty:*</label>
                    <div class="col-lg-5">
                        <input style="height: 60px;width:350px;font-size: 40px;" height="40px" type="date" class="form-control" name="date_of_duty" id="date_of_duty" value="<?php if ($this->input->post('date_of_duty')) { echo $this->input->post('date_of_duty');} else { echo $current_date; }?>" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Use Schcdule:*</label>
                    <div class="col-lg-3">
                        <select class="form-control" name="duty_version" id="duty_version">
                            <?php foreach ($duty_version_list as $key => $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo ($selected_version == $value->id)? 'selected':'';?>><?=$value->name;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit No.*</label>
                    <div class="col-lg-3">
                        <input readonly="readonly" style="background-color:#999999;cursor: not-allowed;height: 100px;width: 150px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit" id="unit" min="1" maxlength="3" value="<?=$selected_unit;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-3 control-label">Time-In:*</label>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_min');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_hour" id="time_r_hour" min="0" maxlength="2" value="<?=$time_r_hour;?>">
                    </div>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_period');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_min" id="time_r_min" min="0" maxlength="2" value="<?=$time_r_min;?>">
                    </div>
                    <div class="col-lg-1" style="margin-right:21px;">
                        <select onchange="mirror_me(this);" class="form-control" name="time_r_period" id="time_r_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" <?php echo ($selected_in_ampm == 'AM')? 'selected':'';?>>AM</option>
                            <option value="PM" <?php echo ($selected_in_ampm == 'PM')? 'selected':'';?>>PM</option>
                        </select>
                    </div>
                    <label for="lastName" class="col-lg-2 control-label">Time-Out:*</label>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_c_min');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_hour" id="time_c_hour" min="0" maxlength="2" value="<?=$time_c_hour;?>">
                    </div>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_c_period');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_c_min" id="time_c_min" min="0   " maxlength="2" value="<?=$time_c_min;?>">
                    </div>
                    <div class="col-lg-1">
                        <select class="form-control" name="time_c_period" id="time_c_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" <?php echo ($selected_out_ampm == 'AM')? 'selected':'';?>>AM</option>
                            <option value="PM" <?php echo ($selected_out_ampm == 'AM')? 'selected':'';?>>PM</option>
                        </select>
                    </div>
                </div>
        </fieldset>
    </div>
</div>

<div class="modal-footer">
    <input type="hidden" name="hidden_ids[]" value="<?=$original_data[0]->unit;?>">
    <input type="hidden" name="hidden_ids[]" value="<?=$original_data[0]->attendance_date;?>">
    <input type="hidden" name="hidden_ids[]" value="<?=$original_data[0]->schedule;?>">
    <input type="hidden" name="hidden_ids[]" value="<?=$original_data[0]->duty_version;?>">

    <input type="hidden" name="hidden_selected[]" value="<?=$original_selected_unit;?>">
    <input type="hidden" name="hidden_selected[]" value="<?=$original_selected_from;?>">
    <input type="hidden" name="hidden_selected[]" value="<?=$original_selected_to;?>">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-success" onclick="submitUpdateDuty()">Update</button>
</div>
</form>
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

    function submitUpdateDuty() {
        document.getElementById("update_duty_attendance").submit();
    }
</script>