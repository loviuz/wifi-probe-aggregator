<?php
header('Access-Control-Allow-Origin: *');

// Lettura file di configurazione
$config = parse_ini_file('../config.ini');

// Array per la risposta del web service
$result = [];


switch( $_POST['op'] ){
    case 'get-last-devices':
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
}
