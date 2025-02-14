<?php
function log_attempt($username, $status) {
    $log_file = '../logs/login_attempts.log';
    $date = date('Y-m-d H:i:s');
    file_put_contents($log_file, "$date - Username: $username - Status: $status\n", FILE_APPEND);
}
?>
