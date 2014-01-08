<?php
// index.php
session_start();

if ((isset($_SESSION['cloudnsru'])) & (isset($_SESSION['cloudnsru']['member_id']))) {
	include (__DIR__ . '/zones.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Главная</title>
</head>
<body>
	<div class="container_12">
		<div class="grid_12" class="alert-zone"></div>
		<div class="grid_6">
			<h2>Вход</h2>
			<form method="post" id="formlogin">
				<input type="hidden" name="method" value="member_auth" />
				Email <input type="text" name="username" id="username" value="" placeholder="my@email.com" class="" />
				Password <input type="password" name="password" id="password" value="" placeholder="Пароль" class="" />
				<input type="submit" class="submit" value="Войти" /><br/>
				<a href="#" class="">Восстановить пароль</a>
			</form>
		</div>
		<div class="grid_6">
			<h2>Регистрация</h2>
			<form method="post" id="formregistration">
				<input type="hidden" name="method" value="member_add" id="member_add" />
				Email <input type="text" name="username" id="username" value="" placeholder="my@email.com" class="" />
				Password <input type="password" name="password" id="password" value="" placeholder="Пароль" class="" />
				<input type="submit" value="Войти" />
			</form>
		</div>
		<hr class="clear" />
	</div>
	<script src="http://yandex.st/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/dns.index.js"></script>
</body>
</html>