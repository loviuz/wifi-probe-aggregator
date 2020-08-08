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
                    $('#devices-table > tbody').prepend(
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

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Add a helper to format timestamp data
Date.prototype.formatMMDDYYYY = function() {
    return (this.getMonth() + 1) +
    "/" +  this.getDate() +
    "/" +  this.getFullYear();
}

function drawLineChart( id, resource ) {
    $.post( reader_url, { op: resource }, function(response){
        response = $.parseJSON(response);

        // Split timestamp and data into separate arrays
        var labels = [], data=[]
        for( i=0; i<response.records.length; i++ ){
            result = response.records[i];
            
            labels.push(result.indice);
            data.push(parseInt(result.valore));
        }

        // Create the chart.js data structure using 'labels' and 'data'
        var full_chart_data = {
            labels : labels,
            datasets : [{
                fillColor             : "rgba(151,187,205,0.2)",
                strokeColor           : "rgba(151,187,205,1)",
                pointColor            : "rgba(151,187,205,1)",
                pointStrokeColor      : "#fff",
                pointHighlightFill    : "#fff",
                pointHighlightStroke  : "rgba(151,187,205,1)",
                backgroundColor       : "#4e73df",
                hoverBackgroundColor  : "#2e59d9",
                borderColor           : "#4e73df",
                data                  : data
            }]
        };

        // Get the context of the canvas element we want to select
        var ctx = document.getElementById(id).getContext("2d");

        new Chart(ctx, {
            type: 'bar',
            data: full_chart_data,
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: true,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return tooltipItem.yLabel + ' devices';
                        }
                    }
                },
            }
        });
    });
}