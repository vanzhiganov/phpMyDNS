<?php
// zones.php
@session_start();
$zone_name = (isset($_REQUEST['zone'])) ? $_REQUEST['zone']: null;

if($zone_name == null) {
	header("location: ./");
	exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Главная</title>
</head>
<body>
	<div class="container_12">
		<div class="grid_12" id="alert-zone"></div>
		<div class="grid_12">
			<div id="zone_name"><h3><?php echo $zone_name;?></h3></div>
			<form method="post" id="formrecordadd">
				<input type="hidden" name="zone" value="<?php echo $zone_name; ?>" />
				Хост <input type="text" name="host" value="" />
				Тип <select name="type">
					<option value="A">A</option>
					<option value="CNAME">CNAME</option>
					<option value="AAAA">AAAA</option>
					<option value="TXT">TXT</option>
					<option value="NS">NS</option>
					<option value="MX">MX</option>
					<option value="SRV">SRV</option>
				</select>
			</form>

			<table id="records">
				<thead>
					<tr>
						<th>Хост</th>
						<th>Тип</th>
						<th>Значение записи</th>
						<th>Приоритет</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<hr class="clear" />
	</div>
	<script src="http://yandex.st/jquery/1.9.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			record_get('<?php echo $zone_name; ?>');
		});
		function record_get(zone_name) {
			$.ajax({
				type:"POST",
				url:"action.php",
				data:'method=record_get&zone='+zone_name,
				cache: false,
				success:function(json){
					var record = jQuery.parseJSON(json);
					if (record.status == 0) {
						//$("#zone_name").append(zone_name);
						if (record.total == 0) {
							$('#alert-zone').append("<div class='alert'>Нет ниодной записи</div>");
						} else {
							$('#records tbody').append("<tr><td><a href='#' onclick='record_get(\""+name+"\")'>"+name+"</a></td></tr>");
						}
					} else {
						$(".alert-zone").append("Error #"+record);
					}
				}
			});
		}
	</script>
</body>
</html>