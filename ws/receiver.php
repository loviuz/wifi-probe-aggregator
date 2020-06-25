<?php

// Dati in arrivo dai readers
$post = file_get_contents('php://input');

$data = json_decode( $post );

// TODO: inserimento a database