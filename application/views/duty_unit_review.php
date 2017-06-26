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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages':['corechart','bar']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawTardiness);

        function drawTardiness() {
            var jsonData = $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'index.php/duty/get_tardiness' ?>",
                dataType: "json",
                async: false,
                data: {
                    'unit': $('#unit').val()
                }
            }).responseText;

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.arrayToDataTable(JSON.parse(jsonData));
            var chart = new google.visualization.AreaChart(document.getElementById('unit_tardiness'));
            chart.draw(data, {width: 1000, height: 150, colors: ['red']});
        }
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
                    <th>Status</th>
                    <th>Using Schedule</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $total = 0; $total_duty=0;$total_add=0; $late_cnt=0;
                        foreach($duty_list as $value) { ?>
                        <tr>
                            <td><?php echo $total+=1;?></td>
                            <td><?php echo $value->unit;?></td>
                            <td><?php echo date('M d, Y - l |',strtotime($value->attendance_date)). ' '. $value->time_in. ' - ' .$value->time_out;?></td>
                            <td>
                                    <?php if ($value->remarks == 'DUTY') { echo '<strong>'.$value->remarks.'</strong>'; $total_duty += 1;}
                                        else { echo '<span style="color:grey">'.$value->remarks.'</span>'; $total_add+=1;}?>
                            </td>
                            <td><?php
                                if ($value->time_in > '09:30 PM') { echo '<span style="color:red">Late!</span>'; $late_cnt+=1;}
                                else echo '<span style="color:green">OK</span>';?></td>
                            <td><?php echo $value->version_name;?></td>
                            <td>
                                <a href="<?=base_url();?>index.php/duty/delete_unit_duty/<?=$value->unit;?>/<?=$value->attendance_date;?>/<?=$value->schedule;?>/<?=$value->duty_version;?>" onclick="return messagePrompt()"><span class="glyphicon glyphicon-remove"></span></a> |
                                <a data-toggle="modal" data-target=".bs-example-modal-md"
                                   href="<?=base_url();?>index.php/duty/duty_unit_edit/<?=$value->unit;?>/<?=$value->attendance_date;?>/<?=$value->schedule;?>/<?=$value->duty_version;?>/<?=$selected_unit;?>/<?=$selected_from;?>/<?=$selected_to;?>">Edit</a>
                            </td>
                        </tr>
                    <?php }?>
                    <?php if ($selected_unit != ""):?>
                    <tr style="background-color: white;">
                        <td colspan="7">
                            <div class="col-lg-12">
                                <strong>Tardiness of <?=date("Y");?>:</strong>
                                <div id="unit_tardiness">Loading...</div>
                            </div>
                        </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td colspan="7"><strong>Total Rendered:</strong> <?=$total;?> | <strong>DUTY:</strong> <?=$total_duty;?>/<?=$required_duty;?> | <strong>ADD:</strong> <?=$total_add;?> | <span style="color:red">Absences**: <?=$required_duty-$total_duty;?></span>,  <span style="color:red">Lates: <?=$late_cnt;?></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span style="font-style: italic;color:grey;font-size:12px;">** Absences may be due to selected date range that is not being rendered by them yet. <br>
                                ** Recent duties might not yet been recorded vs. the required duties. <br>
                                Please mind the date when querying. </span></td>
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