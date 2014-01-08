<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Регистрация</title>
</head>
<body>
	<h1>Регистрация</h1>
	<div>
		<form action="action.php" method="post">
			<input type="hidden" name="method" value="member_add" />
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