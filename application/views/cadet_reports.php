<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?= base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="<?= base_url();?>bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <?php include 'base.inc'; ?>
    <div>&nbsp;</div>
    <div class="col-lg-11">
        <fieldset>
            <legend>Cadet Reports Options</legend>
            <form class="form-horizontal" role="form" action='<?= base_url();?>index.php/main/cadet_reports' method="post">
                <div class="form-group">
                    <div class="bs-example" data-example-id="single-button-dropdown" style="margin-left: 15px;">
                        <label for="title" class="control-label">From:*</label>
                        <div class="btn-group" style="width:150px">
                            <input type="date" class="form-control" name="select_from" id="select_from" value="<?php echo $select_from;?>" >
                        </div>
                        <label for="title" class="control-label">To:*</label>
                        <div class="btn-group" style="width:150px">
                            <input type="date" class="form-control" name="select_to" id="select_to" value="<?php echo $select_to;?>">
                        </div>
                        <label for="title" class="control-label">Version:*</label>
                        <div class="btn-group" style="width:200px">
                            <select class="form-control" name="cadet_version" id="cadet_version" >
                                <option value="">Choose</option>
                                <?php foreach ($cadet_version as $key => $value) { ?>
                                    <option value="<?=$value->id;?>" <?php echo ($selected_version == $value->id)? 'selected':'';?>><?=$value->name;?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="title" class="control-label">Sort By:*</label>
                        <div class="btn-group">
                            <select class="form-control" name="sort_by" id="sort_by">
                                <option value="0" <?php if ($sort_by == 0) echo 'selected';?>>Unit</option>
                                <option value="1" <?php if ($sort_by == 1) echo 'selected';?>>Highest</option>
                                <option value="2" <?php if ($sort_by == 2) echo 'selected';?>>Lowest</option>
                            </select>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success" >Search</button>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main/cadet_reports" class="btn btn-default" >Clear</a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url();?>index.php/main" class="btn btn-info" >Home</a>
                        </div>
                    </div>
                </div>
            </form>
            <div>&nbsp;</div>
            Shown From <span style="color:red;font-size:16px;"><?=date('M d, Y',strtotime($select_from)); ;?></span> -- <span style="color:red;font-size:16px;"><?=date('M d, Y',strtotime($select_to)); ;?></span>
            <span style="float:right;font-size:18px">&nbsp;Total in Days: <strong><?=$total_duties;?></strong></span>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size:14px;">
                <thead>
                <tr>
                    <th>UNIT #</th>
                    <th>NAME</th>
                    <th>Total Rendered</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($duties)) {
                    foreach ($duties as $key => $n) {?>
                        <tr>
                            <td><?php echo $n->unit; ?></td>
                            <td><?php echo $n->full_name; ?></td>
                            <td><?php echo $n->total_duties; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else {
                    echo '<tr><td colspan="9">No records found.</td></tr>';
                }?>
                </tbody>
                <tr   style="background-color: white;">
                    <td colspan="8"><span style="font-style: italic;color:grey;font-size:12px;">** Red color is at < 75%<br>
                                Please mind the date when querying. </span></td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
</body>
</html>