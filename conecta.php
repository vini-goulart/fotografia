<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
if(!session_id()) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB Localhost
$user = 'magnetofoto';
$password = 'M@gF0t0_0962';
$db = 'magnetofoto';
$host = 'magnetofoto.mysql.dbaas.com.br';

$link = mysqli_init();
$success = mysqli_real_connect(
   $link,
   $host,
   $user,
   $password,
   $db,
   $port
);
mysqli_set_charset($link,"utf8");
?>
