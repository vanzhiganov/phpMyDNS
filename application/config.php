<?php
/**
 * @file: config.php
 */

$PG_HOST = "localhost";
$PG_USER = "user";
$PG_PASSWORD = "password";
$PG_DBNAME = "dbname";

if (file_exists("./config_local.php")) {
	include "./config_local.php";
}