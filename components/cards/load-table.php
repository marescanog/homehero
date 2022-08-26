<?php 
    // var_dump($_POST)
    $title = isset($_POST["title"]) ? $_POST["title"] : "For the time period";
    $chartLabels = isset($_POST["chartLabels"]) ? $_POST["chartLabels"] : [];
    $chartData = isset($_POST["chartData"]) ? $_POST["chartData"] : [];
    $table_data_per_team = isset($_POST["table_data_per_team"]) ? $_POST["table_data_per_team"] : null;
    $my_team = isset($_POST["my_team"]) ? $_POST["my_team"] : null;
    $filterType = isset($_POST["filterType"]) ? $_POST["filterType"] : 1;
    $table_data_per_team_totals = isset($_POST["table_data_per_team_totals"]) ? $_POST["table_data_per_team_totals"] : [] ;
    // var_dump($my_team);
    // var_dump($table_data_per_team);
?>

<div>
<h2><?php echo $title;?></h2>
    <div class="mt-1 mb-5 table-responsive">
        <table class="table table-striped table-sm">
        <thead>
            <tr>
            <th>Date</th>
            <?php 
                if($filterType != 2){
            ?>
                <th>Total Number of Tickets</th>
            <?php 
                } else {
            ?>
                 <!-- <th>Total Number of Tickets</th> -->
                 <?php 
                        for($xt=0; $xt<count($my_team); $xt++){
                ?>
                        <th><?php echo  $my_team[$xt]['first_name'].' '.($my_team[$xt]['last_name'][0].'.');?></th>
                <?php 
                    }
                ?>
            <?php 
                }
            ?>
            </tr>
        </thead>
        <tbody>
            
                <?php 
                if($filterType != 2){
                    $sum=0;
                    for($xt=0; $xt<count($chartLabels); $xt++){
                        $sum += $chartData[$xt];
                ?>
                    <tr>
                        <td><?php echo  $chartLabels[$xt];?></td>
                        <td><?php echo  $chartData[$xt];?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td>TOTAL SUM</td>
                        <td><?php echo  $sum;?></td>
                    </tr>
                <?php
                } else {
                    for($xt=0; $xt<count($chartLabels); $xt++){
                ?>
                    <tr>
                        <!-- Date -->
                        <td><?php echo  $chartLabels[$xt];?></td>
                        <!-- AgentS -->
                        <?php           
                            if($table_data_per_team != null){
                                for($xaa=0; $xaa<count($my_team); $xaa++){
                        ?> <!-- ------------------------------------------------------------------ -->
                                <td><?php echo $table_data_per_team[$xt][$my_team[$xaa]['id']];?></td>
                        <?php
                                }
                            }
                    }
                        ?>
                    </tr>

                    <tr>
                        <td><b>TOTAL SUM</b></td>
                        <?php 
                        // var_dump( $table_data_per_team_totals);
                             if( $table_data_per_team_totals != null){
                                for($cookie=0;$cookie<count($table_data_per_team_totals);$cookie++){
                        ?>
                                    <td><?php echo  $table_data_per_team_totals[$cookie];?></td>
                        <?php 
                                }
                            }
                        ?>
                     <tr>

                <?php
                    }
                ?>

            <!-- <tr><td>ipsum</td>
            <td>dolor</td>
            <td>sit</td> </tr> -->
       
        </tbody>
        </table>
    </div>
    </div>
</div>