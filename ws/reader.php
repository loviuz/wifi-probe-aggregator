<?php
header('Access-Control-Allow-Origin: *');

// Lettura file di configurazione
$config = parse_ini_file('../config.ini');

// Array per la risposta del web service
$result = [];


switch( $_POST['op'] ){
    case 'get-last-devices':
        // Vendor cache
        $oui = file_get_contents($config['oui_path']);

        if (!empty($_POST['date_start']) && !empty($_POST['date_end'])) {
            // Connessione al database
            $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

            // Errore nella connessione a database
            if (mysqli_connect_errno($mysqli)) {
                    
                $result = [
                    'status' => 'ERR',
                    'message' => mysqli_connect_error(),
                ];
            } else {
                // Lettura device fra le date richieste
                $sql = "SELECT DISTINCT logs.mac, logs.ssid, logs.dbm, logs_data.received_at, devices.nome FROM logs LEFT JOIN devices ON logs.mac=devices.mac INNER JOIN (SELECT mac, MAX(received_at) AS received_at FROM logs GROUP BY mac) AS logs_data ON logs_data.mac=logs.mac WHERE logs_data.received_at BETWEEN ? AND ? ORDER BY logs_data.received_at DESC";
                $stmt = $mysqli->prepare($sql);
                
                // Errore nella preparazione query
                if (!$stmt) {
                    $result = [
                        'status' => 'ERR',
                        'message' => $mysqli->error,
                    ];
                } else {
                    
                    if ($stmt->bind_param('ss', $date_start, $date_end)) {
                        $date_start = $_POST['date_start'];
                        $date_end = $_POST['date_end'];

                        // Esecuzione statement
                        $stmt->execute();

                        $rs = $stmt->get_result();
                        
                        while ($row = $rs->fetch_assoc()) {
                            // Conversione del segnale in percentuale se richiesto
                            if ($_POST['signal_type'] == 'percent') {
                                if ($row['dbm'] <= -100) {
                                    $row['dbm'] = 0;
                                } elseif ($row['dbm'] >= -50) {
                                    $row['dbm'] = 100;
                                } else {
                                    $row['dbm'] = 2 * ($row['dbm'] + 100);
                                }
                            }

                            // Lettura vendor
                            $mac = str_replace( ':', '-', substr($row['mac'], 0, 8) );

                            $row['vendor'] = '';

                            if( preg_match( '/^'.preg_quote($mac).'([\s\t]+)\(hex\)(.+?)$/im', $oui, $m) ){
                                $row['vendor'] = trim($m[2]);
                            }

                            $records[] = $row;
                        }

                        $result = [
                            'status' => 'OK',
                            'records' => (array)$records,
                        ];
                    } else {
                        $result = [
                            'status' => 'ERR',
                            'message' => 'Errore durante l\'esecuzione query',
                        ];
                    }

                }
            }

            echo json_encode($result);

            $stmt->close();
            $mysqli->close();
        }
    break;

    case 'get-online-devices':
        if (!empty($_POST['date_start']) && !empty($_POST['date_end'])) {
            // Connessione al database
            $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

            // Errore nella connessione a database
            if (mysqli_connect_errno($mysqli)) {
                    
                $result = [
                    'status' => 'ERR',
                    'message' => mysqli_connect_error(),
                ];
            } else {
                // Lettura numero di device unico nell'intervallo di date
                $sql = "SELECT DISTINCT logs.mac FROM logs WHERE received_at BETWEEN ? AND ?";
                $stmt = $mysqli->prepare($sql);
                
                // Errore nella preparazione query
                if (!$stmt) {
                    $result = [
                        'status' => 'ERR',
                        'message' => $mysqli->error,
                    ];
                } else {
                    
                    if ($stmt->bind_param('ss', $date_start, $date_end)) {
                        $date_start = $_POST['date_start'];
                        $date_end = $_POST['date_end'];

                        // Esecuzione statement
                        $stmt->execute();

                        $rs = $stmt->get_result();
                        
                        while ($row = $rs->fetch_assoc()) {
                            // Conversione del segnale in percentuale se richiesto
                            if ($_POST['signal_type'] == 'percent') {
                                if ($row['dbm'] <= -100) {
                                    $row['dbm'] = 0;
                                } elseif ($row['dbm'] >= -50) {
                                    $row['dbm'] = 100;
                                } else {
                                    $row['dbm'] = 2 * ($row['dbm'] + 100);
                                }
                            }

                            $records[] = $row;
                        }

                        $result = [
                            'status' => 'OK',
                            'records' => sizeof($records),
                        ];
                    } else {
                        $result = [
                            'status' => 'ERR',
                            'message' => 'Errore durante l\'esecuzione query',
                        ];
                    }

                }
            }

            echo json_encode($result);

            $stmt->close();
            $mysqli->close();
        }
    break;


    case 'get-devices-by-hour':
        // Connessione al database
        $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        // Errore nella connessione a database
        if (mysqli_connect_errno($mysqli)) {
                
            $result = [
                'status' => 'ERR',
                'message' => mysqli_connect_error(),
            ];
        } else {
            // Lettura numero di device unico nell'intervallo di date
            $sql = "
                SELECT
                    CONCAT( DATE_FORMAT(received_at, '%H'), ':00' ) AS indice,
                    COUNT(DISTINCT(mac)) AS valore
                FROM
                    `logs`
                GROUP BY
                    DATE_FORMAT(received_at, '%H')";

            $stmt = $mysqli->prepare($sql);
            
            // Errore nella preparazione query
            if (!$stmt) {
                $result = [
                    'status' => 'ERR',
                    'message' => $mysqli->error,
                ];
            } else {
                // Esecuzione statement
                $stmt->execute();

                $rs = $stmt->get_result();
                
                while ($row = $rs->fetch_assoc()) {
                    $records[] = $row;
                }

                $result = [
                    'status' => 'OK',
                    'records' => $records,
                ];
            }
        }

        echo json_encode($result);

        $stmt->close();
        $mysqli->close();
    break;


    case 'get-devices-by-weekday':
        $weekdays = [
            0 => 'domenica',
            1 => 'lunedì',
            2 => 'martedì',
            3 => 'mercoledì',
            4 => 'giovedì',
            5 => 'venerdì',
            6 => 'sabato',
        ];

        // Connessione al database
        $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        // Errore nella connessione a database
        if (mysqli_connect_errno($mysqli)) {
                
            $result = [
                'status' => 'ERR',
                'message' => mysqli_connect_error(),
            ];
        } else {
            // Lettura numero di device unico nell'intervallo di date
            $sql = "
                SELECT
                    DATE_FORMAT(received_at, '%w') AS indice,
                    COUNT(DISTINCT(mac)) AS valore
                FROM
                    `logs`
                GROUP BY
                    DATE_FORMAT(received_at, '%w')";

            $stmt = $mysqli->prepare($sql);
            
            // Errore nella preparazione query
            if (!$stmt) {
                $result = [
                    'status' => 'ERR',
                    'message' => $mysqli->error,
                ];
            } else {
                // Esecuzione statement
                $stmt->execute();

                $rs = $stmt->get_result();
                
                while ($row = $rs->fetch_assoc()) {
                    $row['indice'] = $weekdays[ $row['indice'] ];
                    $records[] = $row;
                }

                $result = [
                    'status' => 'OK',
                    'records' => $records,
                ];
            }
        }

        echo json_encode($result);

        $stmt->close();
        $mysqli->close();
    break;


    case 'get-devices-by-vendor':
        // Vendor cache
        $oui = file_get_contents($config['oui_path']);

        // Connessione al database
        $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        // Errore nella connessione a database
        if (mysqli_connect_errno($mysqli)) {
                
            $result = [
                'status' => 'ERR',
                'message' => mysqli_connect_error(),
            ];
        } else {
            $sql = "
                SELECT
                    SUBSTRING(mac, 1, 8) AS indice,
                    COUNT(DISTINCT(mac)) AS valore
                FROM
                    `logs`
                GROUP BY
                    SUBSTRING(mac, 1, 8)
                ORDER BY
                    valore DESC
                LIMIT
                    0, 10";

            $stmt = $mysqli->prepare($sql);
            
            // Errore nella preparazione query
            if (!$stmt) {
                $result = [
                    'status' => 'ERR',
                    'message' => $mysqli->error,
                ];
            } else {
                // Esecuzione statement
                $stmt->execute();

                $rs = $stmt->get_result();
                
                while ($row = $rs->fetch_assoc()) {
                    // Lettura vendor
                    $mac = str_replace( ':', '-', substr($row['indice'], 0, 8) );

                    $row['valore_extra'] = 'sconosciuto';

                    if( preg_match( '/^'.preg_quote($mac).'([\s\t]+)\(hex\)(.+?)$/im', $oui, $m) ){
                        $row['valore_extra'] = trim($m[2]);
                    }

                    $records[] = $row;
                }

                $result = [
                    'status' => 'OK',
                    'records' => $records,
                ];
            }
        }

        echo json_encode($result);

        $stmt->close();
        $mysqli->close();
    break;
}
