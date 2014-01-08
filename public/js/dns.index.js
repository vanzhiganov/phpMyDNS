$(document).ready(function() {
	$("#formlogin .submit").click(function(){
		var method = $("#method").val();
		var username = $("#username").val();
		var password = $("#password").val();
		
		$(".alert-zone").hide();
		
		if(username == '' || password == '') {
			$("#alert-zone").show();
			$("#alert-zone").fadeIn(400).html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><span><b>Ошибка!</b> Е-майл или пароль некоректны.&nbsp;&nbsp;</span></div>');
		} else {
			$("#alert-zone").show();
			$("#alert-zone").fadeIn(400).html('<div class="alert alert-info"><a class="close" data-dismiss="info">×</a><span><img src="img/loader16.gif" align="absmiddle"> Авторизация...</span></div>');
			$.ajax({
				type:"POST",
				url:"action.php",
				data:'method=member_auth&username='+username+'&password='+password,cache: false,success:function(json){
					//alert(json);
					var response = jQuery.parseJSON(json);

					switch (response.error){
						case "11":
							$(".alert").hide();
							$("#alert-zone").fadeIn(400).html('<div class="alert alert-error"><a class="close" data-dismiss="error">×</a><span><b>Внимание!</b> Вы уже авторизованы. Перейти к <a href="zones.php">списку зон</a>.</span></div>');
						break;
						case "10":
							$(".alert").hide();
							$("#alert-zone").fadeIn(400).html('<div class="alert alert-error"><a class="close" data-dismiss="error">×</a><span><b>Ошибка!</b> Е-майл или пароль некоректны.&nbsp;&nbsp;</span></div>');
						break;
						case "0":
							$(".alert").hide();
							$("#alert-zone").fadeIn(400).html('<div class="alert alert-success"><a class="close" data-dismiss="success">×</a><span><b>Успешная авторизация!</b> Если автоматическое перенаправление не произошло, то перейдите по <a href="zones.php">ссылке</a>.</span></div>');
							window.location.href = "zones.php";
						break;
					}
				}
			});
		}
		return false;
	});
});
