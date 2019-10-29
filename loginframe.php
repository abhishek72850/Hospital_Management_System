<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<link rel="stylesheet" type="text/css" href="css/loginlight.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>	
</head>
<body>
	<div class="loginlight">
		<div>
			<div>
				<h3>Login</h3>
				<p>Get access to your Appointments, Favourite Hospitals and much more.</p>
			</div>
		</div>
		<div class="formcontainer">
			<div>
				<form id="lightloginform" action="#" autocomplete="off">
					<div>
						<label for="email">Email or Phone</label>
						<input type="text" id="email" name="email" />
					</div>
					<div>
						<label for="password">Password</label>
						<input maxlength="15" type="password" name="password" id="password" />
					</div>
					<div>
						<input type="submit" value="Log in"/>
					</div>
				</form>
			</div>
			<div>
				<h3>Don't have a account <a href="#" onclick="window.parent.swapToSignup()">Click here</a></h3>
			</div>
		</div>
	</div>
	<div class="loadme"></div>
	<script type="text/javascript">
		$(document).ready(function() {

			$("#lightloginform").submit(function(e){
				e.preventDefault();
				$("input").prop("disabled",true);
				$(".loadme").fadeToggle(500);

				var email=$("#email").val().trim();
				var pass=$("#password").val().trim();

				var json={
				"email":email,
				"password":pass,
				"action":"login"
				};

				var request=$.ajax({
					url:"server/usermanager.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

				request.done(function(data){
					
					data=JSON.parse(data);
					if(!data.success){
						
						$("input").prop("disabled",false);
						$(".loadme").fadeToggle(500);
						alert(data.error);
					}
					else{
						$("input").prop("disabled",true);
						window.parent.location="index.php";
					}
				});
				request.fail(function(jqXhr,data,error){
					console.log(error);
				});
			});
		});
	</script>
</body>
</html>