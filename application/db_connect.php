<?php
// db_connect.php

$link = pg_connect("host={$PG_HOST} dbname={$PG_DBNAME} user={$PG_USER} password={$PG_PASSWORD}");

if (!$link) {
	echo "Произошла ошибка.\n";
	exit;
}