<!DOCTYPE html>
<html>
<head>
	<title>Sign up</title>
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/loginlight.css">
	<link rel="stylesheet" type="text/css" href="css/signuplight.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
</head>
<body>
	<div class="loginlight">
		<div>
			<div>
				<h3>Signup</h3>
				<p>We do not share your personal details with anyone.</p>
			</div>
		</div>
		<div class="formcontainer">
			<div>
				<form id="lightSignupform" action="#" autocomplete="off" >
					<div>
						<label for="email">Email</label>
						<input type="text" id="email" name="email"/>
					</div>
					<div>
						<label for="password">Password</label>
						<input type="password" name="password" id="password"/>
					</div>
					<div>
						<label for="cnfpassword">Confirm Password</label>
						<input type="password" name="cnfpassword" id="cnfpassword"/>
					</div>
					<span style="font-size: 12px;color: #999;margin-top: 10px;">Note: Password Should be between 6-15 characters long.</span>
					<div>
						<input type="submit" value="Sign Up"/>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="loadme"></div>
	<script type="text/javascript">
		$(document).ready(function() {

			$("#lightSignupform").submit(function(e){
				e.preventDefault();
				$("input").prop("disabled",true);
				$(".loadme").fadeToggle(500);

				var email=$("#email").val().trim();
				var pass=$("#password").val().trim();
				var cnfpass=$("#cnfpassword").val().trim();

				var json={
					"email":email,
					"password":pass,
					"cnfpassword":cnfpass,
					"action":"signup"
				}; 
				console.log(json);
				var request=$.ajax({
					url:"server/usermanager.php",
					method:"POST",
					dataType:"text",
					data:json
				});

				request.done(function(data){
					console.log(data);
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