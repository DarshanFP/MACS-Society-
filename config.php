<?php
ob_start();
define('DB_SERVER', 'u441625086_demo_thiruvur');
define('DB_USERNAME', 'u441625086_demoUser');
define('DB_PASSWORD', 'PASSword@222');
//define( 'DB_HOST', '127.0.0.1' );
$subdomain = join('.', explode('.', $_SERVER['HTTP_HOST'], -2));
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
if($subdomain == '' || $subdomain == 'php-manvisoftwares' || $subdomain == 'port-3000-php-manvisoftwares.preview')
    $subdomain = 'macs';
$database = mysqli_select_db($connection,"u441625086_thiruvuru");
?>
