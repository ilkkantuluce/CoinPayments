<?php
$log_file = 'ipn_log.txt';
$log_data = print_r($_POST, true);

file_put_contents($log_file, $log_data, FILE_APPEND);

echo 'IPN received and logged.';
?>
