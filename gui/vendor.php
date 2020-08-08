<?php

header('Content-Type: image/png');

$docroot = realpath(__DIR__);
$vendor_name = preg_replace( '/([^a-z0-9\.\-\(\)\s]+)/i', '', $_GET['name'] );

$vendor_img = $docroot.'/img/vendors/'.$vendor_name.'.png';

if( file_exists($vendor_img) ){
    echo file_get_contents($docroot.'/img/vendors/'.$vendor_name.'.png');
} else {
    echo file_get_contents($docroot.'/img/vendors/unknown.png');
}