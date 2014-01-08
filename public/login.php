<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login</title>
</head>
<body>
	<h1>Авторизация</h1>
	<div class="row">
		<form action="action.php" method="post">
			<input type="hidden" name="method" value="member_auth" />
			Email <input type="text" name="username" value="" placeholder="my@email.com" class="" />
			Пароль <input type="password" name="password" value="" placeholder="Пароль" class="" />
			<input type="submit" value="Войти" /><br/>
			<a href="#" class="">Восстановить пароль</a>
			<a href="#" class="">Зарегистрироваться</a>
		</form>
	</div>
	<script src="http://yandex.st/jquery/1.9.1/jquery.min.js"></script>
</body>
</html>