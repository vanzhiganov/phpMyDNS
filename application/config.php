<?php
/**
 * @file: config.php
 */

$PG_HOST = "localhost";
$PG_USER = "dnsuser";
$PG_PASSWORD = "superPassword";
$PG_DBNAME = "dns";

if (file_exists("./config_local.php")) {
	include "./config_local.php";
}