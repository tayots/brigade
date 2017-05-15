<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function submitDate(el) {
            document.getElementById("review_form").submit();
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend style="color: #337ab7;"><span class="glyphicon glyphicon-list"></span> Review Duties Rendered</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" id="review_form" action='<?= base_url();?>index.php/duty/review' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Date:*</label>
                    </div>
                    <div class="bs-example" data-example-id="single-button-dropdown">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-default" name="prev" value="<?php echo $selected_week-1;?>"><span class="glyphicon glyphicon-chevron-left"></span> Prev</button>
                        </div>
                        <div class="btn-group">
                            <input type="week" class="form-control" name="date_of_duty" id="date_of_duty" value="<?php echo $current_date;?>" autofocus onchange="submitDate(this);">
                        </div>
                        <div class="btn-group">
                            <input type="text" class="form-control" style="width: 45px;" name="selected_week" id="selected_week" value="<?php echo $selected_week;?>" readonly="readonly">
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-default" name="next" value="<?php echo $selected_week+1;?>">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/duty/review" class="btn btn-default" >Today</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
<!--                        <div class="btn-group">-->
<!--                            <a href="--><?//= base_url();?><!--index.php/duty/attendance" class="btn btn-success" >Add</a>-->
<!--                        </div>-->
                        <div class="btn-group" style="margin-left: 33px;">
                            <span style="color:green;"> Required: 3 (Green)</span>
                        </div>
                        <div class="btn-group">
                            , <span style="color:red;"> Low (Red)</span>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Sunday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Sunday)) { echo count($Sunday);}?>]</span></th>
                    <th>Monday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Monday)) { echo count($Monday);}?>]</span></th>
                    <th>Tuesday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Tuesday)) { echo count($Tuesday);}?>]</span></th>
                    <th>Wednesday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Wednesday)) { echo count($Wednesday);}?>]</span></th>
                    <th>Thursday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Thursday)) { echo count($Thursday);}?>]</span></th>
                    <th>Friday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Friday)) { echo count($Friday);}?>]</span></th>
                    <th>Saturday <span style="font-weight: normal;color: #009900;">[<?php if (isset($Saturday)) { echo count($Saturday);}?>]</span></th>
                </tr>
                </thead>
                <tbody>
                <tr style="font-size: 18px;">
                    <?php if (isset($Sunday)) {?><td>
                        <?php foreach ($Sunday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Monday)) {?><td>
                        <?php foreach ($Monday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Tuesday)) {?><td>
                        <?php foreach ($Tuesday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Wednesday)) {?><td>
                        <?php foreach ($Wednesday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Thursday)) {?><td>
                        <?php foreach ($Thursday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Friday)) {?><td>
                        <?php foreach ($Friday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                    <?php if (isset($Saturday)) {?><td>
                        <?php foreach ($Saturday as $key => $value) { echo '<a href="'.base_url().'index.php/duty/delete_duty/'.$value->unit.'/'.$value->attendance_date.'/'.$value->schedule.'">'.$value->unit.' <span class="glyphicon glyphicon-remove"></span></a><br>'; }?>
                        </td><?php } ?>
                </tr>
                </tbody>
            </table>
            <table>
                <tr style="font-size:20px;">
                    <td>Total: </td>
                    <td><strong><?=$total_duties_for_the_week;?></strong></td>
                </tr>
                <tr>
                    <td width="100px">Rendered: </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <?php foreach($personel_duties_for_the_week->result() as $row) {
                                        if ($row->total >= 3) echo '<span style="color:green">'.$row->unit.' - '.$row->total.'</span><br>';
                                        else echo '<span style="color:red">'.$row->unit.' - '.$row->total.'</span><br>';
                                     }?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>