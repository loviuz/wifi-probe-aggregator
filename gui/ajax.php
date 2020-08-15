<?php

$config = parse_ini_file('../config.ini');
include __DIR__.'/lib/util.php';

if( $_GET['op'] == 'get-details' && !empty($_GET['mac']) ){
    ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Orari</h1>
    </div>


    <!-- Dispositivi per orario -->
    <div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Visibilità per orario</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
            <canvas id="chart-orari"></canvas>
            </div>
        </div>
        </div>
    </div>
    </div>


    <!-- Dispositivi per giorno -->
    <div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Visibilità per giorno</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
            <canvas id="chart-giorno"></canvas>
            </div>
        </div>
        </div>
    </div>
    </div>



    <script>
        drawBarChart( 'chart-orari', 'get-activity-by-hour', '<?php echo $_GET['mac']; ?>' );
        drawBarChart( 'chart-giorno', 'get-activity-by-weekday', '<?php echo $_GET['mac']; ?>' );
    </script>
<?php
}