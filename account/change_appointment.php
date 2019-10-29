<?php
	session_start();

	require_once('../server/dbconfig.php');
	require_once('../server/webuser.php');
	
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
		header('location:../');
	}

	$sql="SELECT * FROM appointment WHERE a_id=".$_GET['aid'];

	$result=mysqli_query($db,$sql);

	$row=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Data and Time</title>
	<link rel="stylesheet" type="text/css" href="../css/change_appointment.css">

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/featherlight.js"></script>
	<script type="text/javascript" src="../js/ajax.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			aid:<?php echo $_GET['aid']?>,
			docid:<?php echo $_GET['docid']?>
		};
	</script>
	<form action="#" method="post" id="change_form">
		<div>
			<label>Appointment Date</label>
			<input type="date" name="app_date" value="<?php echo $row['a_date'] ?>">
		</div>
		<div>
			<label>Appointment Time</label>
			<select name="app_time">
				<?php
					$sql="SELECT slot_time FROM time_slot WHERE doc_id=".$_GET['docid'];

					$result=mysqli_query($db,$sql);

					while($row=mysqli_fetch_assoc($result)){
				?>
				<option value="<?php echo $row['slot_time'] ?>"><?php echo $row['slot_time'] ?></option>
				<?php
					}
				?>
			</select>
		</div>
		<div>
			<button type="submit">Change</button>
		</div>
	</form>
	<script type="text/javascript">
		
		$(document).ready(function(){

			$("#change_form").on("submit",function(e){

				e.preventDefault();

				var app_date=this.app_date.value;
				var app_time=this.app_time.value;

				console.log(app_date);
				console.log(app_time);

				$(this).manager({
					url:"../server/hospitalmanager.php",
					data: {
						aid:his.aid,
						app_date:app_date,
						app_time:app_time,
						action:'change_appointment'
					},
					callbackfunc:function(data){
						data=JSON.parse(data);

						console.log(data);

						if(data.success){
							window.parent.location="index.php";
						}
					}
				});
			});
		});
	</script>
</body>
</html>