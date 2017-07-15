<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-11">
        <fieldset>
            <legend><span class="glyphicon glyphicon-book" style="color:blue;"></span> Monthly <span style="color:blue;">Meeting</span>  Reports</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/monthly_meeting_reports' method="post">
                <div class="form-group">
                    <div class="col-lg-2">
                        <label for="title" class="control-label">Select Date:</label>
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
                            <a href="<?= base_url();?>index.php/main/monthly_meeting_reports" class="btn btn-default" >Clear</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size:14px;">
                <thead>
                    <tr>
                        <th>UNIT #</th>
                        <?php if(isset($month_list)){
                            foreach($month_list as $m){
                                echo '<th>'.$m.'</th>';
                            }
                        }?>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($meeting_data)){?>
                        <?php if(isset($month_list)){
                            foreach($unit_list as $u){ $total = 0;
                                echo '<tr>';
                                echo '<td>'.$u->unit.'</td>';
                                foreach($month_list as $m){
                                    if (isset($meeting_data[$u->unit])){
                                        if (isset($meeting_data[$u->unit][$m])) {
                                            $total +=$meeting_data[$u->unit][$m];
                                            echo '<td>'.$meeting_data[$u->unit][$m].'</td>';
                                        }
                                        else echo '<td>0</td>';
                                    }
                                    else {
                                        echo '<td>0</td>';
                                    }
                                }
                                echo '<td>'.$total.'</td>';
                                echo '</tr>';
                            }
                        }?>
                    <?php } else {?>
                        <tr>
                            <td colspan="10">No results found.</td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>