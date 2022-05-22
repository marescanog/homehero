<?php

// Variables for the DOM Elements
    $basicSearchId = isset($basicSearchId) ? $basicSearchId : "";
    $searchCaption = isset($searchCaption) ? $searchCaption : "Search Ticket";

// Variables for the "Showing X out of x"
    $totalRecords = isset($totalRecords) ? $totalRecords : 0;
    $EntriesDisplayed = isset($EntriesDisplayed) ? $EntriesDisplayed : 0;

// Variables for the tables
    $tableName = isset($tableName) ? $tableName: null;

    $tableHeaderLabels = isset($tableHeaderLabels) ? $tableHeaderLabels : 
    ["Ticket No.", "Type", "Status", "Assigned Agent", "Last Updated", "Date Assigned", "Date Created"];

    $tableRows = isset($tableRows) ? $tableRows : [];

    $statusButton = isset($statusButton) ? $statusButton :array(3);

    $buttonClass = isset($buttonClass) ? $buttonClass : [];

    $buttonName = isset($buttonName) ? $buttonName : [];

    $hiddenRows = isset($hiddenRows) ? $hiddenRows : [count($tableHeaderLabels)];

    $ID_row = isset($ID_row) ? $ID_row  : 0;

    $modalButtons = isset($modalButtons) ? $modalButtons  : [];

    $hasClass = isset($hasClass) ? $hasClass  : false;

    // Variables for the pagination
    // Function to check if it is a status button
    
    if (!function_exists('cass')) {
        function cass($yeet, $arrgh) {
            if(in_array($yeet+1, $arrgh)){
                return true;
            }
            return false;
        }
    } 
?>

<div class="main-container d-flex flex-column flex-1 min-height">
    <div>
        <div class="mt-3 row d-flex justify-content-between align-items-center">
            <div class="col-12 col-lg-2 " >
                <p class="showing-x-text ml-2" style="min-width:25em !important;">
                    Showing <?php echo $EntriesDisplayed ;?> 
                    out of <?php echo $totalRecords;?> 
                    Entries  
                </p>
            </div>
            <div class="input-group mb-3 col-12 col-lg-3 mr-2">
                <!-- <div class="input-group-prepend  ">
                    <span class="input-group-text override-input-group" 
                    <?php 
                        // This feature is temporarily removed
                        //echo $basicSearchId == "" ? "" : "id='$basicSearchId'";
                    ?>
                    >
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" class="form-control override-input" placeholder="<?php //echo $searchCaption;?>" aria-label="search" aria-describedby="basic-search"> -->
            </div>
        </div>
        <div class="table-responsive">
            <table id="<?php echo 'table-hook-'.$tableName;?>" class="table table-striped table-sm">
                <!-- TABLE HEADER DATA -->
                <thead>
                <tr>
                    <?php 
                        for($x = 0; $x < count($tableHeaderLabels); $x++){
                    ?>
                    <th><?php echo $tableHeaderLabels[$x] ;?></th>
                    <?php
                        }
                    ?>
                </tr>
                </thead>

                <!-- TABLE BODY DATA -->
                <?php 
                    if(count($tableRows) > 0){
                ?>
                <tbody>
                    <?php
                        for($x=0; $x<count($tableRows); $x++){
                    ?>
                    <tr>
                        <?php 
                        
                            // This row is for the row with a button
                            // Which row the button appears can be customized by declaring a status button. Currently the default is at the third row. 
                            for($y=0; $y < count($tableRows[$x]); $y++){
              

                                if(isset($statusButton) && cass($y, $statusButton) ){
                                // if(isset($statusButton) && $y == $statusButton-1)){ //old code
                        ?>
                            <td class="pr-4">
                                <button 
                                    <?php echo isset($modalButtons) && count($modalButtons) != 0 &&  cass($y, $modalButtons) ? "data-toggle='modal' data-target='#modal' " : ""; ?>

                                    <?php 
                                        echo  $tableName == null ? "" :
                                        "id='btn-".$tableName."-".
                                        (count($statusButton)==1 && $hasClass==false?"":$buttonName[$y]."-".$x."-")
                                        .$tableRows[$x][count($tableRows[0])-1]."'";
                                    ?>
                                class="btn btn-primary btn-table
                                    <?php echo count($statusButton)==1 && $hasClass==false?$tableRows[$x][$y]:$buttonClass[$y];?>
                                ">
                                    <?php echo $tableRows[$x][$y];?>
                                </button>
                            </td>
                        <?php 
                            }  else if (cass($y-1, $hiddenRows)) {
                                // If it is a hidden row
                        ?>
                               <td unselectable="on" style="opacity:0; width:1px;cursor: default;" <?php echo $ID_row == $y ? "id='".$tableName."-".$x."'" : ""; ?>><?php echo $tableRows[$x][$y];?></td>
                        <?php
                                } else {
                                    // Otherwise the row would just be a label
                        ?>
                                    <!-- If an ID row is indicated, it will echo an id html for a hook to grab the id number -->
                                    <td <?php echo $ID_row == $y ? "id='".$tableName."-".$x."'" : ""; ?>><?php echo $tableRows[$x][$y];?></td>
                        <?php 
                                }
                            }
                        ?>
                    </tr>
                    <?php 
                        }
                    ?>
                </tbody>
                <?php 
                    } 
                ?>
            </table>
        </div>
        <?php 
            if(count($tableRows) == 0){
        ?>
            <div>
                <p>No Results Found.</p>
            </div>
        <?php 
            } 
        ?>
    </div>
   
</div>

