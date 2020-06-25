<?php

/**
 * Script per simulare l'invio al web service di MAC address raccolti nelle vicinanze
 */


// URL dell'endpoint che riceve i dati
$receiver_url = "http://localhost/wifi-probe-aggregator/ws/receiver.php";

// Lista di indirizzi MAC
$mac_addresses = [
    '00:03:93:11:22:01', // Apple
    '00:03:93:11:22:02',
    '00:03:93:11:22:03',

    '00:00:F0:11:22:01', // Samsung
    '00:00:F0:11:22:02',
    '00:00:F0:11:22:03',
    '00:00:F0:11:22:04',
    '00:00:F0:11:22:05',
    '00:00:F0:11:22:06',

    '00:18:82:11:22:01', // Huawei
    '00:18:82:11:22:02',

    '00:0C:6E:11:22:01', // Asus
    '00:0C:6E:11:22:02',
    '00:0C:6E:11:22:03',
];


// Lista di Access Point
$ap = [
    'HomeWifi',
    'Vodafone-1200150',
    'Fastweb-555874',
    'MarioRossi-Wifi',
    'Margaret Pizzeria',
    'Tissot&Co',
];


// Preparazione oggetto da inviare
$post_data = [
    "address" => $mac_addresses[ rand(0, sizeof($mac_addresses)-1) ],
    "essid" => $ap[ rand(0, sizeof($ap)-1) ],
    "signal" => rand(0, 100),
    "latitude" => 41.909986,
    "longitude" => 12.3959152,
];

// Invio dati tramite la libreria cURL
$ch = curl_init($receiver_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

// Invio dati
$response = curl_exec($ch);

// Chiusura handler
curl_close($ch);

// Debug
echo $response;
