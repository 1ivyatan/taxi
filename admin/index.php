<?php
    require_once "permscheck.php";
    is_logged_in(true);

    $title = "Pārskats";
    $level = "..";
    require_once $level."/assets/parts/admin/head.php";
?>

<?php

 // just static sql query

$pendingNumberWeek = $db->query("SELECT count(*) as pending FROM `taxi_reservations` WHERE `status` = 'pending' and date_add(`created`, INTERVAL 7 DAY) > now() and `removed` = 0", true)[0][0];

$openedNumberWeek = $db->query("SELECT count(*) as opened FROM `taxi_reservations` WHERE `status` = 'viewed' and date_add(`created`, INTERVAL 7 DAY) > now() and `removed` = 0", true)[0][0];

$completedNumberWeek = $db->query("SELECT count(*) as completed FROM `taxi_reservations` WHERE `status` = 'completed' and date_add(`created`, INTERVAL 7 DAY) > now() and `removed` = 0", true)[0][0];

$fullNumberWeek = $db->query("SELECT count(*) as week FROM `taxi_reservations` WHERE date_add(`created`, INTERVAL 7 DAY) > now()  and `removed` = 0", true)[0][0];



$pendingNumber = $db->query("SELECT count(*) as pending FROM `taxi_reservations` WHERE `status` = 'pending'  and `removed` = 0", true)[0][0];

$openedNumber = $db->query("SELECT count(*) as opened FROM `taxi_reservations` WHERE `status` = 'viewed' and `removed` = 0", true)[0][0];

$completedNumber = $db->query("SELECT count(*) as completed FROM `taxi_reservations` WHERE `status` = 'completed' and `removed` = 0", true)[0][0];

$fullNumber = $db->query("SELECT count(*) as week FROM `taxi_reservations` where `removed` = 0", true)[0][0];



?>

<main class="container">
    <p class="h3">Sveicināti, <?= $_SESSION[$SESSIONCREDS]["name"] ?>!</p>


    <div class="d-flex justify-content-center row-gap-3 column-gap-3 flex-wrap">




    <div class="card">
        <div class="card-header">
            <strong>Pieteikumi šājā nedēļā...</strong>
        </div>
        <div class="card-body d-flex justify-content-center row-gap-3 column-gap-3 flex-wrap ">

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $pendingNumberWeek ?>
                        </div>
                        Jauni
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $openedNumberWeek ?>
                        </div>
                        Atvērti
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $completedNumberWeek ?>
                        </div>
                        Pabeigti
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $fullNumberWeek ?>
                        </div>
                        Kopā
                    </h6>
                </div>
            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <strong>Pieteikumiem kopā...</strong>
        </div>



        <div class="card-body d-flex justify-content-center row-gap-3 column-gap-3 flex-wrap ">

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $pendingNumber ?>
                        </div>
                        Jauni
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $openedNumber ?>
                        </div>
                        Atvērti
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $completedNumber ?>
                        </div>
                        Pabeigti
                    </h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <div class="rounded-circle badge bg-primary text-white">
                            <?= $fullNumber ?>
                        </div>
                        Kopā
                    </h6>
                </div>
            </div>

        </div>



    </div>

    </div>
    <br>





    <div class="d-flex justify-content-center row-gap-3 column-gap-3 flex-wrap">
    
    <?php 
    
    $newestlist = $db->query("SELECT `res_id`, `phone`, `name`, `fname` FROM `taxi_reservations` WHERE `removed` = 0 order by `created` desc LIMIT 7", true);

    
    ?>

    <div class="card">
        <div class="card-header">
            <strong>Jaunākie pieteikumi</strong>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead> 
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tālrunis</th>
                    <th scope="col">Vārds</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($newestlist as $item): ?>
                        <tr>
                            <td><?= $item["res_id"] ?></td>
                            <td><?= $item["phone"] ?></td>
                            <td><?= $item["name"] ?> <?= $item["fname"] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div>

    <?php $timesWeek = $db->query("SELECT cast(`created` as date) as day, count(date) as times FROM `taxi_reservations` group by day order by day asc limit 7", true); ?>
        
        <div class="card">
            <div class="card-header">
                <strong>Pieteikumu skaits pa nedēļu</strong>
            </div>

            <div class="card-body position-static">
                <canvas id="pietChart"></canvas>
            </div>
        </div>


    </div>

  </div>


  <script>
    
    const labels = [ <?php
            foreach ($timesWeek as $piet) {
                echo "'".$piet["day"]."'";

                if (next($timesWeek)) {
                    echo ", ";
                }
            }
        
        ?>];
            const values = [<?php
                
            foreach ($timesWeek as $piet) {
                echo "'".$piet["times"]."'";

                if (next($timesWeek)) {
                    echo ", ";
                }
            }
    ?>];

    


          const countChart = new Chart("pietChart", {


  type: "line",
  data: {
    labels: labels,
    datasets: [{
      fill: true,
      backgroundColor: "rgba(0, 121, 251, .5)",
      borderColor: "#0079FB",
      data: values
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 0, stepSize: 1}}],
    }
  }

            });


  </script>

</main>

<?php include_once $level."/assets/parts/admin/foot.php" ?>