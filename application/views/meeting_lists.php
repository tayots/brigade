<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function loadFireLocation() {
            document.getElementById("meeting_review_form").submit();
        }
        function loadFireAttendance() {
            document.getElementById("meeting_review_form").submit();
        }

        //Edit SL: more universal
        $(document).on('hidden.bs.modal', function (e) {
            $(e.target).removeData('bs.modal');
        });
    </script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-12">
        <fieldset>
            <legend style="color: blue;"><span class="glyphicon glyphicon-list"></span> Browse Meetings</legend>
            <form class="form-horizontal" role="form" id="meeting_review_form" action='<?= base_url();?>index.php/meeting/lists' method="post">
                <div class="form-group">
                   <div class="col-lg-1">
                       <label for="title" class="control-label">From:*</label>
                   </div>
                   <div class="col-lg-2">
                       <input type="date" onchange="loadFireLocation();" class="form-control" name="from_date" id="from_date" value="<?=$from_date;?>" autofocus>
                   </div>
                    <div class="col-lg-1">
                        <label for="title" class="control-label">To:*</label>
                    </div>
                    <div class="col-lg-2">
                        <input type="date" onchange="loadFireLocation();" class="form-control" name="to_date" id="to_date" value="<?=$to_date;?>" autofocus>
                    </div>
                   <div class="col-lg-4">
                        <div class="bs-example" data-example-id="single-button-dropdown">
                           <div class="btn-group">
                               <button type="submit" class="btn btn-default" name="prev" value=""><span class="glyphicon glyphicon-chevron-left"></span> Prev</button>
                           </div>
                           <div class="btn-group">
                               <a href="<?= base_url();?>index.php/meeting/lists" class="btn btn-default" >This Month</a>
                           </div>
                           <div class="btn-group">
                               <button type="submit" class="btn btn-default" name="next" value="">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
                           </div>
                            <div class="btn-group">
                                <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br>
            Results:
            <div >
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Date of meeting</th>
                            <th>Venue</th>
                            <th>Activities</th>
                            <th style="text-align: center;">Total Present</th>
                            <th style="text-align: center;">OIC</th>
                            <th style="text-align: center;">Recorder</th>
                            <th style="text-align: center;">Reviewed by</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($meeting_list) > 0) {?>
                            <?php foreach ($meeting_list as $key => $value) {?>
                            <tr>
                                <td title="show details"><a href="meeting_details/<?=$value->id;?>" data-toggle="modal" data-target="#myLargeModalLabel"><?php echo date('l, M d, Y',strtotime($value->date_of_meeting)); ?> <span class="glyphicon glyphicon-share"></span></a></td>
                                <td><?php echo $value->venue; ?></td>
                                <td><?php echo $value->activity; ?></td>
                                <td style="text-align: center" title="show details"><a href="meeting_details/<?=$value->id;?>" data-toggle="modal" data-target="#myLargeModalLabel"><?php echo $value->total; ?> <span class="glyphicon glyphicon-share"></span></a></td>
                                <td style="text-align: center"><?php echo $value->oic; ?></td>
                                <td style="text-align: center"><?php echo $value->recorder; ?></td>
                                <td style="text-align: center">
                                    <?php if($value->approved_by == null){ ?>
                                        <a href="meeting_details/<?=$value->id;?>" data-toggle="modal" data-target="#myLargeModalLabel">Review</a>
                                    <?php } else { ?>
                                    <?php echo $value->approved_by; ?>
                                    <?php } ?>
                                </td>
                                <td title="show actions">
                                    <a href="meeting_details/<?=$value->id;?>" data-toggle="modal" data-target="#myLargeModalLabel">View</a> |
                                    <a href="meeting_edit/<?=$value->id;?>" data-toggle="modal" data-target="#editmeetingDetails">Edit</a> |
                                    <?php if($value->approved_by == null){ ?>
                                    <a href="#" onclick="return sendmeeting(<?=$value->id;?>);">Send</a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else {?>
                            <tr>
                                <td colspan="8">No Results Found. Please select another date range.</td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>
<!-- Modal -->
<div id="myLargeModalLabel" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Meeting Details</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>
<!-- Modal EDIT-->
<div id="editmeetingDetails" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit meeting</h4>
            </div>
            <div class="modal-body">
                You are not allowed to edit the content. Please contact Administrator Unit 94.
            </div>
        </div>
    </div>
</div>
<div id="loadingModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Sending.... <img src="<?= base_url();?>image/giphy.gif" width="64px"></h4>
            </div>
            <div class="modal-body">
                Please do not close the browser yet.
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    function sendmeeting(meeting_id) {
        if (confirm('Do you want to email this meeting detail?')){
            var p = prompt('Please enter email address(es) (comma separated)');
            if (p != ''){
                var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                var result = p.replace(/\s/g, "").split(/,|;/);
                for(var i = 0;i < result.length;i++) {
                    if(!regex.test(result[i])) {
                        alert('You have not entered any valid email.');
                        return false;
                    }
                }
                var person = { email: p, meeting_id: meeting_id };
                $('#loadingModal').modal('show');
                $.ajax({
                    url: 'meeting_email',
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data == 1) location.reload();
                    },
                    data: person
                });
            } else alert('You have not entered any valid email.');
        }
        else return false;
    }
</script>
</html>
