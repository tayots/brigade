<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<div class="container">
    <div class="col-lg-9">
    <fieldset>
        <legend style="padding-top:10px;color: #990000"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>  Fire Details</legend>
        <?php if (isset($fire_data)) { ?>
            <?php foreach ($fire_data as $key => $value) { ?>
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
                            <?php echo $value->water_used; ?> Tons
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Base Operator:</label>
                        </div>
                        <div class="col-lg-4">
                            <?php echo $value->unit; ?>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Office-In-Charge:</label>
                        </div>
                        <div class="col-lg-4">
                            <?php echo $value->oic; ?>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members Proceeding:</label>
                        </div>
                        <div class="col-lg-4">
                            <?php echo $value->proceeding; ?>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-2">
                            <label for="title" class="control-label">Members at base:</label>
                        </div>
                        <div class="col-lg-4">
                            <?php echo $value->at_base; ?>
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
                <div class="col-lg-2">
                    <?php if ($value->dispatch == 'No'){ echo '<span style="color:red">NO DISPATCH</span>'; } else { echo 'Dispatch';} ?>
                    <?php if (isset($fire_apparata)) { ?>
                    <textarea cols="42px" rows="8" style="white-space: pre-line;"><?php foreach ($fire_apparata as $key => $value) {
                            echo '#'.$value->engine.'('.$value->fto_out.') - OUT:'.$value->time_out.' -> IN:'.$value->time_in.' ('.$value->fto_in.")\n";
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