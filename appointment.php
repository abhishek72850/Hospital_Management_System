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

	$hid=$_GET['hid'];
	$doc=$_GET['docid'];

	$sql="SELECT * from hospital h,doctor d,department dp,address a where d.doc_id=$doc and h.h_id=$hid and d.d_id=dp.d_id and a.r_id=$hid";

	$result=mysqli_query($db,$sql);

	$row=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Appointment Form</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/appointment.css">
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.js"></script>
	<script  type="text/javascript" src="js/jquery-ui.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			hid:<?php echo $_GET['hid'] ?>,
			docid:<?php echo $_GET['docid'] ?>,
			pid:"0"
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
		<section class="sub_sec">
			<div class="form_cont">
				<div class="form_sub_sec show_list">
					<h4>+</h4>
					<p>Select Patient info from the list</p>
				</div>
				<div class="form_sub_sec">
					<h4>Appointment Form</h4>
				</div>
				<div class="form_sub_sec">
					<form action="#" method="post" id="appointment_form">
						<fieldset class="field_sub_sec">
							<legend>Pateint Detail</legend>
							<div>
								<input type="text" id="pname" name="patient_name" placeholder="Name of Patient">
							</div>
							<div>
								<input type="text" id="page" name="patient_age" placeholder="Age">
								<div>
									<label for="male">M</label>
									<input type="radio" id="male" name="gender" value="male">
									<label for="female">F</label>
									<input type="radio" id="female" name="gender" value="female">
								</div>
							</div>
							<div>
								<textarea placeholder="Enter Address" id="paddr" name="paddr"></textarea>
							</div>
							<div>
								<input type="text" id="pphone" name="patient_phone" placeholder="Enter Mobile Number">
							</div>
						</fieldset>
						<fieldset class="field_sub_sec">
							<legend>Hospital Detail</legend>
							<div>
								<input type="text" name="hospital_name" value="<?php echo $row['h_name'] ?>" disabled>
							</div>
							<div>
								<textarea disabled name="hospital_addr"><?php echo $row['addr_text'] ?></textarea>
							</div>
						</fieldset>
						<fieldset class="field_sub_sec">
							<legend>Doctor Detail</legend>
							<div>
								<input type="text" name="department_name" value="<?php echo $row['d_name'] ?>">
								<input type="hidden" name="department_id" value="<?php echo $row['d_id'] ?>">
							</div>
							<div>
								<div>
									<img src="#">
								</div>
								<div>
									<h4><?php echo $row['doc_name'] ?></h4>
									<p><?php echo $row['speciality'] ?></p>
									<p><?php echo $row['degree'] ?></p>
									<?php
										$sql="SELECT * FROM time_slot WHERE doc_id=$doc";

										$result=mysqli_query($db,$sql);

										$days=array('Sun'=>0,'Mon' => 1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6);

										$slot_day=mysqli_fetch_assoc($result)['slot_day'];

										$day_arr=explode(',', $slot_day);

									?>
									<p>Available on: <?php echo $slot_day ?></p>
								</div>
							</div>
							<div>
								<label for="app_date">Appointment Date</label>
								<input type="text" name="app_date" id="app_date">
							</div>
							<div>
								<label for="app_time">Appointment Time</label>
								<select name="app_time" id="app_time">
								<?php
									$sql="SELECT * FROM time_slot WHERE doc_id=$doc";

									$result=mysqli_query($db,$sql);

									while($row=mysqli_fetch_assoc($result)){

								?>
									<option value="<?php echo $row['slot_time']?>"><?php echo $row['slot_time']?> AM</option>
								<?php
									}
								?>
								</select>
							</div>
						</fieldset>
						<div class="field_sub_sec">
							<button type="reset">Reset</button>
							<button type="submit">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</section>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){

			var days={
				0:<?php if(in_array('Sun', $day_arr))echo "true";else echo "false";?>,
				1:<?php if(in_array('Mon', $day_arr))echo "true";else echo "false";?>,
				2:<?php if(in_array('Tue', $day_arr))echo "true";else echo "false";?>,
				3:<?php if(in_array('Wed', $day_arr))echo "true";else echo "false";?>,
				4:<?php if(in_array('Thu', $day_arr))echo "true";else echo "false";?>,
				5:<?php if(in_array('Fri', $day_arr))echo "true";else echo "false";?>,
				6:<?php if(in_array('Sat', $day_arr))echo "true";else echo "false";?>
			};

			$('#app_date').datepicker({ 
				minDate: 0, 
				maxDate: "+1M",
				beforeShowDay:function(dt){
					return [days[dt.getDay()]? true : false];
				}
			});

			$('.show_list').on("click",function(){
		 		$.featherlight({iframe: 'patient_list.php?uid='+his.id,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 600,iframeHeight: 500});
		 	});

			$("#appointment_form").on("submit",function(e){

				e.preventDefault();

				var pname=this.patient_name.value;
				var page=this.patient_age.value;
				var pgender=this.gender.value;
				var paddr=this.paddr.value;
				var pmobile=this.patient_phone.value;
				var did=this.department_id.value;
				var app_date=this.app_date.value;
				var app_time=this.app_time.value;

				console.log(app_date);
				console.log(app_time);

				$(this).manager({
					url:"server/hospitalmanager.php",
					data: {
						pid:his.pid,
						uid:his.id,
						pname:pname,
						page:page,
						pgender:pgender,
						paddr:paddr,
						pmobile:pmobile,
						did:did,
						app_date:app_date,
						app_time:app_time,
						action:'set_appointment'
					},
					callbackfunc:function(data){
						data=JSON.parse(data);

						console.log(data);
						
						his.pid="0";

						if(data.success){
							alert("Appointment Set Successfully");
						}

						
					}
				});

			});

		});	

		var getPatient=function(pid,name,phone,age,gender,addr){
			var current=$.featherlight.current();
			current.close();

			his.pid=pid;
			$("#pname").attr('value',name);
			$("#page").attr('value',age);

			if(gender=="Male"){
				$("#male").attr("checked",true);
			}
			else{
				$("#female").attr("checked",true);
			}
			$("#pphone").attr('value',phone);
			$("#paddr").val(addr);
		}
	</script>
</body>
</html>