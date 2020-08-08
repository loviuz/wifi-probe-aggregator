<?php
include('parts/header.php');
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
        <h6 class="m-0 font-weight-bold text-primary">Dispositivi per orario</h6>
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
        <h6 class="m-0 font-weight-bold text-primary">Dispositivi per giorno</h6>
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
  $('#menu-orari').addClass('active');

  drawLineChart( 'chart-orari', 'get-devices-by-hour' );
  drawLineChart( 'chart-giorno', 'get-devices-by-weekday' );
</script>

<?php
include('parts/footer.php');