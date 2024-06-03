<?php
$public_key = '';
$private_key = '';

function coinPaymentsApiCall($private_key, $post_fields)
{
    $url = 'https://www.coinpayments.net/api.php';
    
    $hmac = hash_hmac('sha512', http_build_query($post_fields), $private_key);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    if ($response === false) {
        die('cURL error: ' . curl_error($ch));
    }
    curl_close($ch);
    
    return json_decode($response, true);
}

$coin = 'BTC'; //'ETH', 'LTC'
$ipn_url = 'http://localhost/coinPayments/ipn-handler.php';

$post_fields = array(
    'version' => 1,
    'cmd' => 'get_callback_address',
    'key' => $public_key,
    'format' => 'json',
    'currency' => $coin,
    'ipn_url' => $ipn_url
);

$response = coinPaymentsApiCall($private_key, $post_fields);

if ($response['error'] === 'ok') {
    echo 'Callback address for ' . $coin . ': ' . $response['result']['address'];
} else {
    echo 'Error: ' . $response['error'];
}
?>
