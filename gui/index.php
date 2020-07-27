<?php
include('parts/header.php');
?>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Dispositivi online -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Dispositivi online</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="online-devices">0</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-wifi fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Content Row -->

          <div class="row">

            <!-- Lista dispositivi -->
            <div class="col-lg-9">
              <div class="card shadow mb-4">
                
                <!-- Card Header -->
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ultimi dispositivi rilevati</h6>
                    
                    <div class="pull-right">
                      Visualizza ultimi 
                      <select class="pull-right" id="history-count">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000">1000</option>
                      </select>
                    </div>
                  </div>
  
                  <!-- Card Body -->
                  <div class="card-body">
                    <table class="table table-condensed table-striped table-hover shadow" id="devices-table">
                      <thead>
                        <tr>
                          <th width="80"></th>
                          <th>Device</th>
                          <th width="280">SSID</th>
                          <th width="180">Data/ora</th>
                          <th width="100">Segnale</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>
            </div>

            <!-- Dispositivi per vendor -->
            <div class="col-lg-3">
              <div class="card shadow mb-4">
                
              <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Vendor</h6>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="vendor-chart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Samsung
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Apple
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Huawei
                    </span>
                  </div>
                </div>

              </div>
            </div>
          </div>

<?php
include('parts/footer.php');