<?php
// Variables for the DOM Elements
    $basicSearchId = isset($basicSearchId) ? $basicSearchId : "";

// Variables for the "Showing X out of x"
    $totalRecords = isset($totalRecords) ? $totalRecords : 0;
    $EntriesDisplayed = isset($EntriesDisplayed) ? $EntriesDisplayed : 0;

// Variables for the tables
    $tableName = isset($tableName) ? $tableName: null;

    $tableHeaderLabels = isset($tableHeaderLabels) ? $tableHeaderLabels : 
    ["Ticket No.", "Type", "Status", "Assigned Agent", "Last Updated", "Date Assigned", "Date Created"];

    $tableRows = isset($tableRows) ? $tableRows : [];

    $statusButton = isset($statusButton) ? $statusButton : 3;

// Variables for the pagination


?>

<div class="main-container d-flex flex-column flex-1 min-height">
    <div>
        <div class="mt-3 row d-flex justify-content-between align-items-center">
            <div class="col-12 col-lg-2 ">
                <p class="showing-x-text ml-2">
                    Showing <?php echo $EntriesDisplayed ;?> 
                    out of <?php echo $totalRecords;?> 
                    Entries  
                </p>
            </div>
            <div class="input-group mb-3 col-12 col-lg-3 mr-2">
                <div class="input-group-prepend  ">
                    <span class="input-group-text override-input-group" 
                    <?php 
                        echo $basicSearchId == "" ? "" : "id='$basicSearchId'";
                    ?>
                    >
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" class="form-control override-input" placeholder="Search Ticket" aria-label="search" aria-describedby="basic-search">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
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
                            for($y=0; $y < count($tableRows[$x]) -1; $y++){
                                if(isset($statusButton) && $y == $statusButton-1){
                        ?>
                            <td class="pr-4">
                                <button 
                                    <?php 
                                        echo  $tableName == null ? "" :
                                        "id='".$tableName."-".$tableRows[$x][count($tableRows[0])-1]."'";
                                    ?>
                                class="btn btn-primary btn-table
                                    <?php echo $tableRows[$x][$y];?>
                                ">
                                    <?php echo $tableRows[$x][$y];?>
                                </button>
                            </td>
                        <?php
                                } else {
                        ?>
                                    <td><?php echo $tableRows[$x][$y];?></td>
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