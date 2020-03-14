<?php 
require_once('config.php'); // config.php ga ham qarab qo'shing.
require_once('eskiz.php');

$gateway = new eskiz(); // you can send credentials

$data = [
    "mobile_phone" => 998941234567, // raqamni almashtirish yoddan chiqmasin. :)
    "message" => "SMS boryaptimi?"
];


$response = $gateway->sendSMS($data);

print_r($response);