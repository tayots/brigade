<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function beforeDelete() {
            if (confirm('Are you sure you want to delete this?\nYou can no longer associate it with reports.')) {
                return true;
            }
            return false;
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Attendance <img src="<?= base_url();?>image/logo.png" width="64px"> Tracker</h2>
    <div>&nbsp;</div>
    <div class="col-lg-10">
        <fieldset>
            <legend>Personnel</legend>
            <?php if(isset($message)){?>
                <div class="row">
                    <div class="col-lg-12"><div class="alert alert-<?=$alert_type;?>"><?=$message?></div></div>
                </div>
            <?php }?>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/personnel' method="post">
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">First Name:*</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Last Name:*</label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstName" class="col-lg-3 control-label">Unit Number:*</label>
                    <div class="col-lg-7">
                        <input style="width:50px;" type="text" class="form-control" name="unit_number" id="unit_number" maxlength="3">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-7" style="text-align: right;">
                        <button type="submit" class="btn btn-success" >Save</button>
                        <a href="<?= base_url();?>index.php/main" class="btn btn-info">Home</a>
                    </div>
                </div>
            </form>
            Results:
            <div style="overflow-y: scroll; height: 250px;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                        <?php if (isset($information)) {  $total = 0;?>
                            <?php foreach ($information as $key => $value) { $total += 1;?>
                            <tr>
                                <td><?php echo $value->first_name; ?></td>
                                <td><?php echo $value->last_name; ?></td>
                                <td style="text-align: center;"><?php echo $value->unit; ?></td>
                                <td><a href="<?= base_url();?>index.php/main/delete_personnel/<?php echo $value->id; ?>" onclick="return beforeDelete();">Delete</a></td>
                            </tr>
                            <?php } ?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div style="font-size: 20px;">Total: <strong><?=$total;?></strong></div>
        </fieldset>
    </div>
</div>
</body>
</html>