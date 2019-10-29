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
		$user=new WebUser(substr(session_id(),0,20),WebUser::TYPE_GUEST);
		$_SESSION['id']=$user->getUID();
		$_SESSION['name']='Login/Signup';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Name of Hospital</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/hinfo.css">
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			hid:<?php echo $_GET['hid'] ?>
		};
	</script>
	<div id="body_cont">
		<header class="sub_sec">
			<div class="header_cont">
				<div class="logo_cont">
					<img src="#">
				</div>
				<div class="profile_cont <?php if($user->getType()==WebUser::TYPE_SELF){ echo 'profile_logged_cont';} ?>">
					<button id="loginCheck">
						<?php
							if($user->getType()==WebUser::TYPE_SELF){
 								echo $user->getFirstName();
 							}
 							else if($user->getType()==WebUser::TYPE_GUEST){
 								echo $_SESSION['name'];
 							}
						?>
					</button>
					<?php
						if($user->getType()==WebUser::TYPE_SELF){
					?>
					<div class="logout_cont">
						<a href="account/">View Profile</a>
						<a href="logout.php">Logout</a>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</header>
		<?php
			$query="SELECT * FROM hospital h,address a WHERE h.h_id=a.r_id AND h.h_id=".$_GET['hid'];

			//echo $query;

			$result=mysqli_query($db,$query);

			$row=mysqli_fetch_assoc($result);
		?>
		<section class="sub_sec">
			<div class="display_cont">
				<div>
					<img src="<?php echo $row['h_pic'] ?>">
				</div>
				<div>
					<h4><?php echo $row['h_name'] ?></h4>
					<address><?php echo $row['addr_text'] ?></address>
				</div>
				<?php
					$isFav=false;
					if($user->getType()==WebUser::TYPE_SELF){
						$sql2="SELECT * FROM favourite_hospital WHERE uid=".$_SESSION['id']." AND hid=".$_GET['hid'];

						$res=mysqli_query($db,$sql2);

						if(mysqli_num_rows($res)>0){
							$isFav=true;
						}
					}

					if(!$isFav || $user->getType()==WebUser::TYPE_GUEST){
				?>
				<div class="addfav">
					<button>Add to Favourite</button>
				</div>
				<?php
					}
				?>
			</div>
			<div class="option_cont">
				<div class="option_card appoint">
					<h4>Appointment</h4>
				</div>
				<div class="option_card about_click">
					<h4>About Us</h4>
				</div>

				<?php
					$query="SELECT count(d.doc_id) AS dcnt,count(dp.d_id) AS dpcnt from doctor d,department dp where d.d_id=dp.d_id and dp.h_id=".$_GET['hid'];

					$result=mysqli_query($db,$query);

					$row=mysqli_fetch_assoc($result);
				?>

				<div class="option_card about">
					<div>
						<h5>Doctors</h5>
						<h1><?php echo $row['dcnt']; ?></h1>
					</div>
					<div>
						<h5>Departments</h5>
						<h1><?php echo $row['dpcnt']; ?></h1>
					</div>
				</div>
			</div>
		</section>
		<footer>
		
		</footer>
	</div>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){

			$('.about').hide();

			$('.appoint').on("click",function(){
		 		$.featherlight({iframe: 'department.php?hid='+his.hid,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 600,iframeHeight: 500});
		 	});

		 	$('.about_click').on('click',function(){
		 		$('.about').fadeToggle("slow","swing",function(){
		 			$('.about').toggleClass('about_active');
		 		});
		 	});

		 	$('.addfav>button').on('click',function(){

		 		if(his.islogin){
		 			$(this).manager({
						url:"server/usermanager.php",
						data: {
							email:his.email,
							uid:his.id,
							hid:his.hid,
							action:'add_fav'
						},
						callbackfunc:function(data){
							//data=JSON.parse(data);

							console.log(data);
							window.location='hinfo.php?hid='+his.hid;
						}
					});
		 		}
		 		else{
		 			closeAndLogin();
		 		}
		 	});
		});	


		var closeAndLogin=function(){
			var current = $.featherlight.current();
			if(current!=null)
				current.close();

			$('#loginCheck').trigger("click");
		}
	</script>
</body>
</html>