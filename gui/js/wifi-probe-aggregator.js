$(document).ready( function(){
    // Intervallo in secondi fra ogni lettura dati
    var interval = 5;

    // Timeout per indicare che un dispositivo era online
    var timeout_online_check = 30;

    // Prime lettura
    leggi_devices(interval);
    online_devices(timeout_online_check);

    // Lettura dispositivi ogni X secondi
    setInterval(
        function(){
            leggi_devices(interval);
        },
        interval*1000
    );

    // Lettura numero di dispositivi ogni 30 secondi
    setInterval(
        function(){
            online_devices(timeout_online_check);
        },
        interval*1000
    );
});

function leggi_devices(interval){
    var date_start = moment().subtract(interval, 'seconds').format('YYYY-MM-DD H:mm:ss');
    var date_end = moment().format('YYYY-MM-DD H:mm:ss');

    // Data di inizio e fine per la richiesta
    // Lettura periodica dispositivi per aggiunta alla tabella principale
    $.post( reader_url, { op: 'get-last-devices', date_start: date_start, date_end: date_end, signal_type: 'percent' }, function(result){
        if (result.status == 'OK') {
            // Lista dispositivi
            for (i=0; i<result.records.length; i++) {
                // Colorazione progress bar in base alla qualità segnale
                if( result.records[i].dbm >= 75 ){
                    progress_class = 'success';
                } else if( result.records[i].dbm >= 30 ){
                    progress_class = 'warning';
                } else {
                    progress_class = 'danger';
                }

                $("#devices-table > tbody").find("tr:gt(" + ( $('#history-count').val() - 2) + ")").remove();

                // Nome dispositivo (se c'è, altrimenti solo MAC address)
                if( result.records[i].nome != null ){
                    device = '<b class="text-success">' + result.records[i].nome + '</b> <small class="text-muted">(' + result.records[i].mac + ')</small>'
                } else {
                    device = result.records[i].mac;
                }

                // Aggiungo il dispositivo in lista se ancora non c'è
                row_id = 'mac_' + (result.records[i].mac).replace(/:/g, '_');

                if( $('#devices-table > tbody').find('tr[data-id="' + row_id + '"]').length == 0 ){
                    $('#devices-table > tbody').append(
                        '<tr data-id="' + row_id + '">' +
                        '<td></td>' + 
                        '<td>' + device + '</td>' +
                        '<td>' + result.records[i].ssid + '</td>' +
                        '<td><time class="timeago" title="' + moment(result.records[i].received_at).format('DD/MM/YYYY H:mm:ss') + '" datetime="' + result.records[i].received_at + '"></time></td>' +
                        '<td><div class="progress"><div class="progress-bar bg-' + progress_class + '" style="width:' + result.records[i].dbm + '%;"></div></div></td>' +
                        '</tr>'
                    );
                }
            }

            $("time.timeago").timeago();
        } else {
            alert( result.message );
        }
    }, 'json');
}


function online_devices(interval){
    var date_start = moment().subtract(interval, 'seconds').format('YYYY-MM-DD H:mm:ss');
    var date_end = moment().format('YYYY-MM-DD H:mm:ss');

    // Data di inizio e fine per la richiesta
    // Lettura periodica dispositivi per aggiunta alla tabella principale
    $.post( reader_url, { op: 'get-online-devices', date_start: date_start, date_end: date_end }, function(result){
        if (result.status == 'OK') {
            $('#online-devices').text(result.records);
        }
    }, 'json');
}