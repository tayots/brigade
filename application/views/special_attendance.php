<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var i=getlatestAddr();

            $("#add_row").click(function(){
                //check duplicate
                if (checkduplicate(i) == true) { return false; }

                $('#addr'+i).html("<td>"+ (i+1) +"</td><td><input id='member"+i+"' name='member"+i+"' type='text' placeholder='Unit Number' class='form-control input-md' maxlength='3'/> </td>");

                $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
                $('#member'+i).focus();
                $('#member_counter').val(i+1);
                i++;
            });
            $("#delete_row").click(function(){
                if(i>1){
                    $("#addr"+(i-1)).html('');
                    $('#member_counter').val(i-1);
                    i--;
                }
            });

            function checkduplicate(i) {
                var i = i - 1;
                var flag = false;
                for (var x=0; x<$('#member_counter').val(); x++) {
                    if (i != x) {
                        if ($('#member'+i).val() == $('#member'+x).val()) {
                            alert('Duplicate Unit found!');
                            $('#member'+i).val('');
                            flag = true;
                        }
                    }
                }
                return flag;
            }

            function getlatestAddr(){
                var a = 0;
                for (var x=0; x<=parseInt($('#member_counter').val()); x++) {
                    if (document.getElementById('addr'+x)) {
                        a = x;
                    }
                }
                return a;
            }

            $('#checkfirst').click(function(){
                if (confirmMessage())
                    document.getElementById("special_attendance_form").submit();
            });

            function confirmMessage(){
                var t = 'Special Activity Date: '+$('#date_of_special').val();
                var m = 'Members Attended: ';
                for (var x=0; x<getlatestAddr(); x++) {
                    if (document.getElementById('addr'+x) !== 'undefined') {
                        m += $('#member'+x).val()+', ';
                    }
                }

                if (confirm('Are you sure you want to save the ff data? \n\n'+t+'\n'+m+'\n\nKindly check if everything is correct.\nOtherwise, click \'Ok\' to procceed.')){
                    return true
                }
                else return false
            }

        });
    </script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend style="color: deepskyblue;"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Log Special Attendance</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" id="special_attendance_form" action='<?= base_url();?>index.php/special/attendance' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Date of Activity:*</label>
                    <div class="col-lg-3">
                        <input tabindex="0" type="date" class="form-control" name="date_of_special" id="date_of_special" value="<?php if ($this->input->post('date_of_special')) { echo $this->input->post('date_of_special');} else { echo $current_date; }?>" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Special Activity:*</label>
                    <div class="col-lg-6">
                        <input tabindex="0" type="text" class="form-control" name="activity" id="activity" value="<?=$this->input->post('activity');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Venue:*</label>
                    <div class="col-lg-4">
                        <input tabindex="0" type="text" class="form-control" name="venue" id="venue" value="<?=$this->input->post('venue');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Members Attended:*</label>
                    <div class="col-lg-5">
                        <input type="hidden" name="member_counter" id="member_counter" value="<?php if (isset($_POST['member_counter'])) echo $_POST['member_counter']; else echo 1;?>">
                        <table class="table table-bordered table-hover" id="tab_logic">
                            <?php if (isset($_POST['member_counter'])) {?>
                                <tbody>
                                <?php for($x=0; $x<$_POST['member_counter']; $x++){?>
                                    <tr id='addr<?=$x;?>'>
                                        <td>
                                            <?=$x+1?>
                                        </td>
                                        <td>
                                            <input tabindex="0" type="text" id='member<?=$x;?>' name='member<?=$x;?>'  placeholder='Unit Number' class="form-control" maxlength="3" value="<?=$_POST['member'.$x];?>"/>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr id='addr<?=$x?>'></tr>
                                </tbody>
                            <?php } else { ?>
                            <tbody>
                            <tr id='addr0'>
                                <td>
                                    1
                                </td>
                                <td>
                                    <input tabindex="0" type="text" id='member0' name='member0'  placeholder='Unit Number' class="form-control" maxlength="3"/>
                                </td>
                            </tr>
                            <tr id='addr1'></tr>
                            </tbody>
                            <?php } ?>
                        </table>
                        <a href="javascript:void(0);" id="add_row" class="btn btn-default pull-left" tabindex="0"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Row</a><a id='delete_row' class="pull-right btn btn-default"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Delete Row</a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Chief Officer:*</label>
                    <div class="col-lg-1">
                        <input style="text-align: center;" type="text" class="form-control" name="oic" id="oic" min="2" maxlength="3" value="<?=$this->input->post('oic');?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Remarks of Activity:*</label>
                    <div class="col-lg-5">
                        <textarea name="remarks" id="remarks" cols="45" rows="5"><?=$this->input->post('remarks');?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Recorded by:*</label>
                    <div class="col-lg-1">
                        <input style="text-align: center;" type="text" class="form-control" name="recorder" id="recorder" min="2" maxlength="3" value="<?=$this->input->post('recorder');?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3">&nbsp;</div>
                    <div class="col-lg-3" style="text-align: right;">
                        <button type="button" class="btn btn-success" id="checkfirst">Check First</button>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</div>
</body>
</html>