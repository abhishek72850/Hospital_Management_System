<?php
	session_start();

	require_once('server/dbconfig.php');
	require_once('server/webuser.php');
	
	$user=null;

	$database=new Database();
	$db=$database->getDbConnection();
	//if user is logged in before
	if(isset($_COOKIE["type"])){

		$user=new WebUser($_COOKIE['id'],$_COOKIE['email'],$_COOKIE['type']);

		if($_COOKIE['type']==WebUser::TYPE_SELF){
			$user->setSelfSession();
		}
	}
	else{
		header('location:index.php');
	}

	$sql="SELECT * FROM patient p,address a WHERE p.p_id=a.r_id AND p.u_id=".$_GET['uid'];

	$result=mysqli_query($db,$sql);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Patient List</title>
	<link rel="stylesheet" type="text/css" href="css/plist.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.js"></script>
</head>
<body>
	<div>
		<?php

		while($row=mysqli_fetch_assoc($result)){
		?>
		<div>
			<div>
				<input type="radio" name="patient" class="patient" value="<?php echo $row['p_id'] ?>">
			</div>
			<div>
				<label>
					<span id="name<?php echo $row['p_id'] ?>"><?php echo $row['p_name'] ?> </span>
					<span id="phone<?php echo $row['p_id'] ?>"> <?php echo $row['p_phone'] ?></span>
					<span id="age<?php echo $row['p_id'] ?>"> <?php echo $row['p_age'] ?></span>
					<span id="gender<?php echo $row['p_id'] ?>"> <?php echo $row['p_gender'] ?></span>
					<span id="address<?php echo $row['p_id'] ?>"> <?php echo $row['addr_text'] ?></span>
				</label>
			</div>
		</div>
		<?php
		}
		?>
		<button id="selectp">Submit</button>
	</div>
	<script type="text/javascript">

		$(document).ready(function(){

			var pid="0";
			var name="";
			var phone="";
			var age="";
			var gender="";
			var addr="";

			$("#selectp").on("click",function(){
				window.parent.getPatient(pid,name,phone,age,gender,addr);
			});

			$('.patient').on("change",function(){
				pid=this.value;

				name=$("#name"+pid).text().trim();
				phone=$("#phone"+pid).text().trim();
				age=$("#age"+pid).text().trim();
				gender=$("#gender"+pid).text().trim();
				addr=$("#address"+pid).text().trim();
			});

		});
	</script>
</body>
</html>