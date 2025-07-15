<?php
// sms_helper.php
require_once '../../db_connect.php';

function sendSMS($number, $message) {
    $apiKey = '487b60aae3df89ca35dc3b4dd69e2518';
    $senderName = 'CaffeLilio';
    
    $parameters = array(
        'apikey' => $apiKey,
        'number' => $number,
        'message' => $message,
        'sendername' => $senderName
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>