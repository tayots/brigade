<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
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
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/fire/data' method="post">
    <?php if ($this->session->flashdata('message')){?>
        <div class="row">
            <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
        </div>
    <?php }?>
    <div class="col-lg-7">
        <fieldset>
            <legend style="color: red"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Fire Data</legend>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Date of Fire:*</label>
                    <div class="col-lg-4">
                        <input type="date" class="form-control" name="date_of_fire" id="date_of_fire" value="<?=$this->input->post('date_of_fire');?>" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Location:*</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="location" id="location" value="<?=$this->input->post('location');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Classification:*</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="classification" id="classification" value="<?=$this->input->post('classification');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Caller:*</label>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" name="caller" id="caller" value="<?=$this->input->post('caller');?>">
                    </div>
                    <label for="firstName" class="col-lg-3 control-label">Contact No.:</label>
                    <div class="col-lg-3">
                        <input type="number" class="form-control" name="contact_number" id="contact_number" value="<?=$this->input->post('contact_number');?>" placeholder="number only">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-lg-3 control-label">Time Received:*</label>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_min');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_hour" id="time_r_hour" min="0" maxlength="2" value="<?=$this->input->post('time_r_hour');?>">
                    </div>
                    <div class="col-lg-1">
                        <input onkeyup="validate_digit(this, 'time_r_period');" onblur="max_two_digit(this);" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;" type="number" class="form-control" name="time_r_min" id="time_r_min" min="0" maxlength="2" value="<?=$this->input->post('time_r_min');?>">
                    </div>
                    <div class="col-lg-1" style="margin-right:21px;">
                        <select onchange="mirror_me(this);" class="form-control" name="time_r_period" id="time_r_period" style="padding:0;height: 40px;width: 60px;font-size: 25px;text-align: center;">
                            <option value="AM" selected>AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <label for="lastName" class="col-lg-2 control-label">Controlled:*</label>
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
                    <label for="firstName" class="col-lg-3 control-label">Water Used:*</label>
                    <div class="col-lg-3">
                        <input type="number" min="0" class="form-control" name="water_used" id="water_used" placeholder="tons" value="<?php echo ($this->input->post('water_used'))? $this->input->post('water_used'):'';?>">
                    </div>
                    <label for="firstName" class="col-lg-3 control-label">Dispatch?:*</label>
                    <div class="col-lg-3">
                        <select name="dispatch" class="form-control" >
                            <option value="Yes" <?php echo ($this->input->post('status') == 'Dispatch') ? 'selected':'';?>>Yes</option>
                            <option value="No" <?php echo ($this->input->post('status') == 'No Dispatch') ? 'selected':'';?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Base Operator:*</label>
                    <div class="col-lg-2">
                        <input style="height: 100px;width: 170px;font-size: 50px;text-align: center;" type="text" class="form-control" name="unit" id="unit" min="1" maxlength="10" value="<?=$this->input->post('unit');?>">
                    </div>
                    <label for="firstName" class="col-lg-3 control-label">O I C:*</label>
                    <div class="col-lg-3">
                        <input style="height: 100px;width: 170px;font-size: 50px;text-align: center;" type="text" class="form-control" name="oic" id="oic" min="1" maxlength="3" value="<?=$this->input->post('oic');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">At Base:</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="at_base" id="at_base"  placeholder="comma separated"  value="<?=$this->input->post('at_base');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Proceeding:</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="proceeding" id="proceeding" placeholder="comma separated" value="<?=$this->input->post('proceeding');?>">
                    </div>
                </div>
        </fieldset>
    </div>
    <div class="col-lg-5">
        <fieldset>
            <legend style="color: red"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Fire Apparata Responded</legend>
            <div style="">
                <span style="float:right;">** please don't skip the row when entering data.</span>
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ENGINE #</th>
                            <th>Time Out</th>
                            <th>FTO</th>
                            <th>Time In</th>
                            <th>FTO</th>
                            <th>On Board</th>
                        </tr>
                    </thead>
                    <?php for ($x=0; $x<=7; $x++) { ?>
                    <tr>
                        <td><input type="text" class="form-control col-xs-4" name="engine[<?=$x;?>]" value="<?=isset($_POST['engine'][$x])? $_POST['engine'][$x]:'';?>"></td>
                        <td><input type="text" class="form-control input-sm" name="time_out[<?=$x;?>]" value="<?=isset($_POST['time_out'][$x])? $_POST['time_out'][$x]:'';?>"></td>
                        <td><input type="text" class="form-control input-sm" name="fto_out[<?=$x;?>]" value="<?=isset($_POST['fto_out'][$x])? $_POST['fto_out'][$x]:'';?>"></td>
                        <td><input type="text" class="form-control input-sm" name="time_in[<?=$x;?>]" value="<?=isset($_POST['time_in'][$x])? $_POST['time_in'][$x]:'';?>"></td>
                        <td><input type="text" class="form-control input-sm" name="fto_in[<?=$x;?>]" value="<?=isset($_POST['fto_in'][$x])? $_POST['fto_in'][$x]:'';?>"></td>
                        <td><input type="text" class="form-control col-xs-4" name="onboard[<?=$x;?>]" value="<?=isset($_POST['onboard'][$x])? $_POST['onboard'][$x]:'';?>"></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="form-group">
                <div class="col-lg-12" style="text-align: right;">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= base_url();?>index.php/fire/data" class="btn btn-primary" >Clear</a>
                    <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                </div>
            </div>
        </fieldset>
    </div>
    </form>
</div>
</body>
</html>