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

    <!-- Dispositivi per giorno (tabella) -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Presenza per giorno</h6>
            </div>
            <div class="card-body">
                <div id="presence-per-day"></div>
            </div>
        </div>
    </div>



    <script>
        drawBarChart( 'chart-orari', 'get-activity-by-hour', '<?php echo $_GET['mac']; ?>' );
        drawBarChart( 'chart-giorno', 'get-activity-by-weekday', '<?php echo $_GET['mac']; ?>' );

        var interval = 30;
        var date_start = moment().subtract(interval, 'days').format('YYYY-MM-DD');
        var date_end = moment().format('YYYY-MM-DD');

        $.post(
            reader_url,
            {
                op: 'get-presence-per-day',
                mac: '<?php echo $_GET['mac']; ?>',
                date_start: date_start,
                date_end: date_end
            }, function(response){
                result = $.parseJSON(response);

                if (result.status == 'OK') {
                    $('#presence-per-day').html(
                        '<table class="table table-striped table-condensed" id="table-presence-per-day">\
                            <thead>\
                                <tr>\
                                    <th width="80%">Giorno</th>\
                                    <th></th>\
                                </tr>\
                            </thead>\
                            <tbody></tbody>\
                        </table>'
                    );

                    // Lista dispositivi
                    var giorno = moment().subtract(interval, 'days');
                    console.log( result.records );

                    for (i=0; i<interval; i++) {
                        icon = '<i class="fa fa-times text-danger"></i>';


                        if( result.records[ giorno.format('YYYY-MM-DD') ] !== undefined ){
                            icon = '<i class="fa fa-check text-success"></i>';
                        }

                        $('#table-presence-per-day tbody').append(
                            '<tr>\
                                <td>' + giorno.format('DD/MM/YYYY') + '</td>\
                                <td class="text-center">' + icon + '</td>\
                            </tr>'
                        );

                        giorno = giorno.add(1, 'days');
                    }
                } else {
                    $('#presence-per-day').html('Errore durante il recupero dati.');
                }
            }
        );
    </script>
<?php
}