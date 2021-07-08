<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="col-lg-9">
    <fieldset>
        <legend style="padding-top:10px;color: green"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span>  Training Details</legend>
        <?php if (isset($training_data)) { ?>
            <?php foreach ($training_data as $key => $value) { $training_id = $value->id; ?>
            <div>
                <div class="col-lg-7">
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Date of Training:</label>
                        </div>
                        <div class="col-lg-3">
                            <span style="color: blue"><?php echo $value->date_of_training;?></span>
                        </div>
                        <div class="col-lg-1">
                            <label for="title" class="control-label">Venue:</label>
                        </div>
                        <div class="col-lg-3">
                            <span><?php echo $value->venue;?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Activity:</label>
                        </div>
                        <div class="col-lg-5">
                            <span><?php echo $value->activity;?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Chief Officer:</label>
                        </div>
                        <div class="col-lg-5">
                            <span><?php echo $value->oic;?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members Present:</label>
                        </div>
                        <div class="col-lg-6">
                            <?php if (isset($training_attendance)) {  $total = 0;?>
                                <?php foreach ($training_attendance as $key => $value2) { $total += 1;?>
                                    <tr>
                                        <span style="color: blue"><u><?php echo $value2->unit; ?></u></span>,
                                    </tr>
                                <?php } ?>
                            <?php }?>
                            (Total of <strong><?=$total;?></strong>)
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Remarks:</label>
                        </div>
                        <div class="col-lg-6">
                            <span><?php echo $value->remarks;?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Recorder by:</label>
                        </div>
                        <div class="col-lg-6">
                            <span><?php echo $value->recorder;?></span>
                        </div>
                    </div>
                    
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Files Activities:</label>
                        </div>
                        <div class="col-lg-6">
                            <?php if (isset($training_attachments)) {  $total = 0;?>
                                <?php foreach ($training_attachments as $key => $value3) { $total += 1;?>
                                    <tr>
                                        <td><a target="_blank" title="<?php echo $value3->file_name;?>" href="<?php echo base_url($value3->file_path.'/'.$value3->file_name);?>"><img width="150px" src="<?php echo base_url($value3->file_path.'/'.$value3->file_name);?>" /></a>&nbsp;</td>
                                    </tr>
                                <?php } ?>
                            <?php }?>
                            (Total of <strong><?=$total;?></strong>)
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        <?php } else {?>
            <div style="height: 200px;">
                No results found. Please select location and date of fire.
            </div>
        <?php } ?>
    </fieldset>
    </div>
</div>
<?php if (isset($training_data)) { ?>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <?php if ($value->approved_by == null) {;?>
    <button type="button" class="btn btn-success" onclick="approveBy(<?=$training_id;?>);">Approve</button>
    <?php } ?>
</div>
<?php } ?>
<script type="text/javascript">
    function approveBy(training_id) {
        var ans = prompt('Key-in your Unit #');
        if (ans != ''){
            $.ajax({url: "approve/"+ans+"/"+training_id, success: function(result){
                if (result == 1){
                    location.reload();
                }
            }, error: function(xhr,status,error) {
                alert(status);
            }});
        }
        return false;
    }
</script>