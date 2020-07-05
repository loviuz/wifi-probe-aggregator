<?php

// Lettura file di configurazione
$config = parse_ini_file('../config.ini');

// Array per la risposta del web service
$result = [];

// Dati in arrivo dai readers
$post = file_get_contents('php://input');

$data = json_decode( $post );

if (!empty($data) ){
    // Connessione al database
    $mysqli = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

    // Errore nella connessione a database
    if (mysqli_connect_errno($mysqli)) {
        
        $result = [
            'status' => 'ERR',
            'message' => mysqli_connect_error(),
        ];
    } else {
        // Preparazione query con prepared statements
        $sql = "INSERT INTO logs (mac, ssid, dbm, latitude, longitude) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        
        // Errore nella preparazione query
        if (!$stmt) {
            $result = [
                'status' => 'ERR',
                'message' => $mysqli->error,
            ];
        } else {
            if ($stmt->bind_param('ssidd', $mac, $ssid, $dbm, $latitude, $longitude)) {
                $mac = $data->address;
                $ssid = $data->essid;
                $dbm = $data->signal;
                $latitude = $data->latitude;
                $longitude = $data->longitude;

                // Esecuzione statement
                $stmt->execute();
                
                $result = [
                    'status' => 'OK',
                    'message' => 'Inserimento avvenuto con successo',
                    'id' => $stmt->insert_id,
                ];
            } else {
                $result = [
                    'status' => 'ERR',
                    'message' => 'Errore durante l\'esecuzione query',
                ];
            }

        }
    }

    $stmt->close();
    $mysqli->close();
} else {
    $result = [
        'status' => 'ERR',
        'message' => 'Nessun dato in input',
    ];
}

echo json_encode($result);