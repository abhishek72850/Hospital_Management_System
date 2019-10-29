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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Favourite Hospital</title>

	<link rel="stylesheet" type="text/css" href="../css/account_common.css">
	<link rel="stylesheet" type="text/css" href="../css/account_fav_hospital.css">

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/featherlight.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>'
		};
	</script>
	<div class="heading">
		<h2>Favourite Hospital</h2>
	</div>
	<div class="fav_cont">
		<?php
			$id=$user->getUID();
			$sql="SELECT * FROM favourite_hospital fh, hospital h, address a WHERE fh.uid=$id and fh.hid=h.h_id and a.r_id=fh.hid";

			$result=mysqli_query($db,$sql);

			while($row=mysqli_fetch_assoc($result)){
		?>
		<div class="fav_card">
			<div class="sub_cont hospital_detail">
				<div>
					<span><?php echo $row['h_name'] ?></span>
				</div>
				<div>
					<address><?php echo $row['addr_text'] ?></address>
				</div>
				<!-- <div>
					<span>Phone Number</span>
				</div> -->
			</div>
			<div class="sub_cont hospital_manage">
				<button onclick="window.parent.location='../hinfo.php?hid='+'<?php echo $row["h_id"] ?>'">View</button>
				<button data-hid="<?php echo $row['h_id'] ?>" class='remove_hospital'>Remove</button>
			</div>
		</div>
		<?php
			}
		?>
		
	</div>
	<script type="text/javascript" src="../js/ajax.js"></script>
	<script type="text/javascript" src="../js/app.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

		 	$('.remove_hospital').on('click',function(){

		 		var hid=this.dataset.hid;

	 			$(this).manager({
					url:"../server/usermanager.php",
					data: {
						email:his.email,
						uid:his.id,
						hid:hid,
						action:'remove_fav'
					},
					callbackfunc:function(data){
						//data=JSON.parse(data);

						console.log(data);
						window.parent.location='index.php';
					}
				});
		 	});
		});
	</script>
</body>
</html>