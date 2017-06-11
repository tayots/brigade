<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function loadFireLocation() {
            document.getElementById("fire_review_form").submit();
        }
        function loadFireAttendance() {
            document.getElementById("fire_review_form").submit();
        }
    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-12">
        <fieldset>
            <legend>Review Fire Response Attendance</legend>
            <form class="form-horizontal" role="form" id="fire_review_form" action='<?= base_url();?>index.php/fire/review_attendance' method="post">
                <div class="form-group">
                   <div class="col-lg-1">
                       <label for="title" class="control-label">When?:*</label>
                   </div>
                   <div class="col-lg-2">
                       <input type="date" onchange="loadFireLocation();" class="form-control" name="date_of_fire" id="date_of_fire" value="<?php if ($this->input->post('date_of_fire')) { echo $this->input->post('date_of_fire');} else { echo $current_date; }?>" autofocus>
                   </div>
                    <div class="col-lg-1">
                        <label for="title" class="control-label">Location:*</label>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" name="location" id="location" onchange="loadFireAttendance();">
                            <option value="">--Select--</option>
                            <?php foreach ($fire_list as $key => $value) { ?>
                                <option value="<?=$value->id;?>" <?php echo ($selected_title == $value->id)? 'selected':'';?>><?=date('F d, Y l',strtotime($value->date_of_fire));?> | <?=$value->location;?> @ <?=$value->time_received;?></option>
                            <?php }?>
                        </select>
                    </div>
                   <div class="col-lg-2">
                        <a href="<?= base_url();?>index.php/fire/review_attendance" class="btn btn-default" >Today</a>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                    </div>
                </div>
            </form>
            <u>Summary:</u>
            <?php if (isset($fire_data)) { ?>
                <?php foreach ($fire_data as $key => $value) { ?>
                <div style="height: 200px;">
                    <div class="container">
                        <div class="col-lg-8">
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Date Time Received:</label>
                                </div>
                                <div class="col-lg-3">
                                    <span style="color: blue"><?php echo '<u>'.date('l, M d, Y',strtotime($value->date_of_fire)).'</u> <u>'.$value->time_received.'</u>'; ?></span>
                                </div>
                                <div class="col-lg-1">
                                    <label for="title" class="control-label">Controlled:</label>
                                </div>
                                <div class="col-lg-4">
                                    <span style="color: blue"><?php echo '<u>'.$value->time_controlled.'</u>'; ?></span>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Classification of Fire:</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $value->classification; ?>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Caller:</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $value->caller; ?> (<?php echo $value->contact_number;?>)
                                </div>
                                <div class="col-lg-1">
                                    <label for="title" class="control-label">Water Used:</label>
                                </div>
                                <div class="col-lg-2">
                                    <?php echo $value->water_used; ?> Tons
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Base Operator:</label>
                                </div>
                                <div class="col-lg-4">
                                    <?php echo $value->unit; ?>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Office-In-Charge:</label>
                                </div>
                                <div class="col-lg-4">
                                    <?php echo $value->oic; ?>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Members Proceeding:</label>
                                </div>
                                <div class="col-lg-4">
                                    <?php echo $value->proceeding; ?>
                                </div>
                            </div>
                            <div class="container">
                                <div class="col-lg-2">
                                    <label for="title" class="control-label">Members at base:</label>
                                </div>
                                <div class="col-lg-4">
                                    <?php echo $value->at_base; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <?php if (isset($fire_apparata)) { ?>
                            <textarea cols="42px" rows="8" style="white-space: pre-line;"><?php foreach ($fire_apparata as $key => $value) {
                                    echo '#'.$value->engine.'('.$value->fto_out.') - OUT:'.$value->time_out.' -> IN:'.$value->time_in.' ('.$value->fto_in.")\n";
                                }?>
                            </textarea>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php } else {?>
                <div style="height: 200px;">
                    No results found. Please select location and date of fire.
                </div>
            <?php } ?>
            Results:
            <div style="overflow-y: scroll; height: 200px;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                        <?php if (isset($information)) {  $total = 0;?>
                            <?php foreach ($information as $key => $value) { $total += 1;?>
                            <tr>
                                <td><?php echo $value->attendance_date; ?></td>
                                <td style="text-align: center;"><?php echo $value->unit; ?></td>
                                <td><?php echo $value->location; ?></td>
                            </tr>
                            <?php } ?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div style="font-size: 20px;">Total Members on Fire Scene: <strong><?php if (isset($total)) echo $total;?></strong></div>
        </fieldset>
    </div>
</div>
</body>
</html>