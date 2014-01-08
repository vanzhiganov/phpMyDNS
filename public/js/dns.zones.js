$(document).ready(function() {
	zone_get();

	$("#formzoneadd").submit(function(){
		var name = $("#name").val();

		if(name == '') {
			$("#alert-zone").show();
			$("#alert-zone").fadeIn(400).html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><span><b>Ошибка!</b> Не указано имя зоны.</span></div>');
		} else {
			$("#alert-zone").show();
			$("#alert-zone").fadeIn(400).html('<div class="alert alert-info"><a class="close" data-dismiss="info">×</a><span><img src="img/loader16.gif" align="absmiddle"> Добавление...</span></div>');
			zone_add(name);
		}
	});
});
function zone_get() {
	$.ajax({
		type:"POST",url:"action.php", data:'method=zone_get', cache: false, success: function(json) {
			var zones = jQuery.parseJSON(json);

			if (zones.errno == 0) {
				if (zones.total > 0) {
					$.each(zones.results, function(key, val) {
						$('#zones tbody').append("<tr><td><a href='records.php?zone="+val.name+"' onclick='record_get(\""+val.name+"\")'>"+val.name+"</td></tr>");
					});
				}
			}
		}
	});
}
function zone_add(zone_name) {
	$.ajax({
		type:"POST",url:"action.php", data:'method=zone_add&name='+zone_name, cache: false, success: function(json) {
			var zone = jQuery.parseJSON(json);
			if (zone.errno == 0) {
				$(".alert").hide();
				$("#alert-zone").fadeIn(400).html('<div class="alert alert-success"><a class="close" data-dismiss="success">×</a><span><b>Успешно!</b> Зона добавлена.</span></div>');

				$('#zones tbody').append("<tr><td><a href='records.php?zone="+name+"' onclick='record_get(\""+name+"\")'>"+name+"</a></td></tr>");
			}
		}
	});
}
