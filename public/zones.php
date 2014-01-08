<?php
// zones.php
@session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Главная</title>
</head>
<body>
	<div class="container_12">
		<div class="grid_12" class="alert-zone"></div>
		<div class="grid_6">
			<table id="zones">
				<thead>
					<tr>
						<th>Имя зоны</th>
					</tr>
					<tr>
						<td>
							<form method="post" id="formzoneadd">
								<input type="text" name="name" id="name" value="" placeholder="yandex.ru" />
								<input type="submit" id="formzoneaddsubmit" value="Добавить" />
							</form>
						</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="grid_6">
			&nbsp;
		</div>
		<hr class="clear" />
	</div>
	<script src="http://yandex.st/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/dns.zones.js"></script>
</body>
</html>