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
            document.getElementById("fire_review_form").submit();
        }
        function loadFireAttendance() {
            document.getElementById("fire_review_form").submit();
        }

        $('#myLargeModalLabel').on('hidden.bs.modal', function (e) {
            alert('asdf');
        });

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
    <div class="col-lg-12">
        <fieldset>
            <legend style="color: red;"><span class="glyphicon glyphicon-fire"></span> Browse Fire Alarms</legend>
            <?php if ($this->session->flashdata('message')){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type'); ?>"><?php echo $this->session->flashdata('message'); ?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" id="fire_review_form" action='<?= base_url();?>index.php/fire/lists' method="post">
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
                   <div class="col-lg-6">
                        <div class="bs-example" data-example-id="single-button-dropdown">
                           <div class="btn-group">
                               <button type="submit" class="btn btn-default" name="prev" value=""><span class="glyphicon glyphicon-chevron-left"></span> Prev</button>
                           </div>
                           <div class="btn-group">
                               <a href="<?= base_url();?>index.php/fire/lists" class="btn btn-default" >This Month</a>
                           </div>
                           <div class="btn-group">
                               <button type="submit" class="btn btn-default" name="next" value="">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
                           </div>
                            <div class="btn-group">
                                <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                            </div>
                            <div class="btn-group">
                                <a href="<?= base_url();?>index.php/fire/attendance" class="btn btn-warning"><span class="glyphicon glyphicon-fire"></span> Log Fire Responses</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br>
            Results: <span style="color:red">NO DISPATCH</span>: <span style="font-size: 18px;"><?=$no_dispatch_count;?></span> ---- <span style="color:green">DISPATCHED</span>: <span style="font-size: 18px;"><?=$dispatch_count;?></span> ---- <span style="font-weight: bold;">TOTAL: <span style="font-size: 18px;"><?=$no_dispatch_count+$dispatch_count;?></span></span>
            <div >
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Date of Fire</th>
                            <th style="text-align: center;">Received</th>
                            <th style="text-align: center;">Controlled</th>
                            <th>Location</th>
                            <th>Classification</th>
                            <th style="text-align: center;">OIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($fire_list) > 0) {?>
                            <?php foreach ($fire_list as $key => $value) {?>
                            <tr>
                                <td title="show details"><a href="fire_details/<?=$value->id;?>" data-toggle="modal" data-target=".bs-example-modal-lg"><?php echo date('l, M d, Y',strtotime($value->date_of_fire)); ?> <span class="glyphicon glyphicon-share"></span></a></td>
                                <td style="text-align: center;"><?php echo $value->time_received; ?></td>
                                <td style="text-align: center;"><?php echo $value->time_controlled; ?></td>
                                <td title="show details"><a href="fire_details/<?=$value->id;?>" data-toggle="modal" data-target=".bs-example-modal-lg"><?php echo $value->location; ?> <span class="glyphicon glyphicon-share"></span></a> <?php if ($value->dispatch == 'No'){ echo '<span style="color:red">NO DISPATCH</span>'; } ?></td>
                                <td title="show details"><a href="fire_details/<?=$value->id;?>" data-toggle="modal" data-target=".bs-example-modal-lg"><?php echo $value->classification; ?> <span class="glyphicon glyphicon-share"></span></a></td>
                                <td style="text-align: center;"><?php echo $value->oic; ?></td>

                            </tr>
                            <?php } ?>
                        <?php } else {?>
                            <tr>
                                <td colspan="6">No Results Found. Please select another date range.</td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>
<!-- Modal -->
<div id="myLargeModalLabel" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Fire Details</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>
</body>
</html>