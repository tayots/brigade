<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function messagePrompt(){
            if(confirm('Are you sure you want to delete?')){
                return true;
            }
            return false;
        }

        //Edit SL: more universal
        $(document).on('hidden.bs.modal', function (e) {
            $(e.target).removeData('bs.modal');
        });
    </script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-11">
        <fieldset>
            <legend style="color: #337ab7;"><span class="glyphicon glyphicon-user"></span> Review Unit Duties Rendered</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" id="review_form" action='<?= base_url();?>index.php/duty/unit_review' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Unit:*</label>
                    </div>
                    <div class="col-lg-2">
                        <select class="form-control" name="unit" id="unit" >
                            <option value="">--Select--</option>
                            <?php foreach ($unit_list as $key => $value) { ?>
                                <option value="<?=$value->unit;?>" <?php echo ($selected_unit == $value->unit)? 'selected':'';?>><?=$value->unit.' '.$value->first_name[0].$value->last_name[0];?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="bs-example" data-example-id="single-button-dropdown">
                        <label for="title" class="control-label">From:*</label>
                        <div class="btn-group">
                             <input type="date" class="form-control" name="select_from" id="select_from" value="<?php echo $selected_from;?>" >
                        </div>
                        <label for="title" class="control-label">To:*</label>
                        <div class="btn-group">
                             <input type="date" class="form-control" name="select_to" id="select_to" value="<?php echo $selected_to;?>">
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success" >Search</button>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/duty/unit_review" class="btn btn-default" >Clear</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Seq.</th>
                    <th>Unit</th>
                    <th>Date/Time</th>
                    <th>Remarks</th>
                    <th>Using Schedule</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $total = 0; $total_duty=0;$total_add=0; foreach($duty_list as $value) { ?>
                        <tr>
                            <td><?php echo $total+=1;?></td>
                            <td><?php echo $value->unit;?></td>
                            <td><?php echo date('M d, Y - l |',strtotime($value->attendance_date)). ' '. $value->time_in. ' - ' .$value->time_out;?></td>
                            <td>
                                    <?php if ($value->remarks == 'DUTY') { echo '<strong>'.$value->remarks.'</strong>'; $total_duty += 1;}
                                        else { echo '<span style="color:grey">'.$value->remarks.'</span>'; $total_add+=1;}?>
                            </td>
                            <td><?php echo $value->version_name;?></td>
                            <td>
                                <a href="<?=base_url();?>index.php/duty/delete_unit_duty/<?=$value->unit;?>/<?=$value->attendance_date;?>/<?=$value->schedule;?>/<?=$value->duty_version;?>" onclick="return messagePrompt()"><span class="glyphicon glyphicon-remove"></span></a> |
                                <a data-toggle="modal" data-target=".bs-example-modal-md"
                                   href="<?=base_url();?>index.php/duty/duty_unit_edit/<?=$value->unit;?>/<?=$value->attendance_date;?>/<?=$value->schedule;?>/<?=$value->duty_version;?>/<?=$selected_unit;?>/<?=$selected_from;?>/<?=$selected_to;?>">Edit</a>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td colspan="6"><strong>Total:</strong> <?=$total;?> | <strong>DUTY:</strong> <?=$total_duty;?> | <strong>ADD:</strong> <?=$total_add;?> </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
<!-- Modal -->
<div id="myDutyModalLabel" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myDutyModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Duty Details</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>
</body>
</html>