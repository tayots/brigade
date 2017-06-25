<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<form action='<?= base_url();?>index.php/fire/update_data' method="post">
<div class="container">
    <div class="col-lg-9">
    <fieldset>
        <legend style="padding-top:10px;color: #990000"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>  Fire Details</legend>
        <?php if ($this->session->flashdata('message2')){?>
            <div class="row">
                <div class="col-lg-12"><div class="alert alert-<?php echo $this->session->flashdata('alert_type2'); ?>"><?php echo $this->session->flashdata('message2'); ?></div></div>
            </div>
        <?php }?>
        <?php if (isset($fire_data)) { ?>
            <?php foreach ($fire_data as $key => $value) { $fire_data_id = $value->id; ?>
            <div style="height: 200px;">
                <div class="col-lg-7">
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Location:</label>
                        </div>
                        <div class="col-lg-3">
                            <span style="color: blue"><?php echo $value->location;?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Date of Fire:</label>
                        </div>
                        <div class="col-lg-3">
                            <span style="color: blue"><?php echo '<u>'.date('l, M d, Y',strtotime($value->date_of_fire)).'</u> '; ?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Time Received:</label>
                        </div>
                        <div class="col-lg-3">
                            <span style="color: blue"><?php echo  '<u>'.$value->time_received.'</u>';; ?></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Time Controlled:</label>
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
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Water Used:</label>
                        </div>
                        <div class="col-lg-2">
                            <input tabindex="0" type="number" min="0" step="any" class="form-control input-sm" style="width:80px" name="water_used" id="water_used" value="<?php echo $value->water_used; ?>" placeholder="Tons">
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Base Operator:</label>
                        </div>
                        <div class="col-lg-4">
                            <input tabindex="0" type="text" class="form-control input-sm" style="width:50px" name="unit" id="unit" value="<?php echo $value->unit; ?>">
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Officer-In-Charge:</label>
                        </div>
                        <div class="col-lg-4">
                            <input tabindex="0" type="text" class="form-control input-sm" style="width:50px" name="oic" id="oic" value="<?php echo $value->oic; ?>">
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members Proceeding:</label>
                        </div>
                        <div class="col-lg-4">
                            <input tabindex="0" type="text" class="form-control input-sm" name="proceeding" id="proceeding" value="<?php echo $value->proceeding; ?>" placeholder="comma separated">
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members at base:</label>
                        </div>
                        <div class="col-lg-4">
                            <input tabindex="0" type="text" class="form-control input-sm" name="at_base" id="at_base" value="<?php echo $value->at_base; ?>" placeholder="comma separated">
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members Responded:</label>
                        </div>
                        <div class="col-lg-4">
                            <?php if (isset($information)) {  $total = 0;?>
                                <?php foreach ($information as $key => $value2) { $total += 1;?>
                                    <tr>
                                        <span style="color: blue"><u><?php echo $value2->unit; ?></u></span>,
                                    </tr>
                                <?php } ?>
                            <?php }?>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">&nbsp;</label>
                        </div>
                        <div class="col-lg-4">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php if ($value->dispatch == 'No'){ echo '<span style="color:white;background-color: red;">.:: NO RESPOND ::.</span> <a href="'.base_url().'index.php/fire/set_status/Yes/'.$fire_data_id.'/'.$from_date.'/'.$to_date.'">Set to Yes?</a>'; } else { echo '<span style="color:white;background-color: green;">.:: RESPONDED ::.</span> <a href="'.base_url().'index.php/fire/set_status/No/'.$fire_data_id.'/'.$from_date.'/'.$to_date.'">Set to No?</a>';} ?>
                    <?php if (isset($fire_apparata)) { ?>
                    <textarea disabled="disabled" cols="42px" rows="8" style="white-space: pre-line;"><?php foreach ($fire_apparata as $key => $value) {
                            echo '#'.$value->engine.'('.$value->fto_out.') - OUT:'.$value->time_out.' -> IN:'.$value->time_in.' ('.$value->fto_in.') ONBOARD:'.$value->onboard."\n";
                        }?>
                    </textarea>
                    <?php } ?>
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

<div class="modal-footer">
    <input type="hidden" name="fire_data_id" value="<?=$fire_data_id;?>">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">Update</button>
</div>
</form>