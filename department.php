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

	$query="SELECT * FROM department WHERE h_id=".$_GET['hid'];

	$result=mysqli_query($db,$query);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Hospital Department</title>
	<link rel="stylesheet" type="text/css" href="css/department.css">
	<link rel="stylesheet" type="text/css" href="css/featherlight.css" />

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
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
	<div>
		<div class="head_cont">
			<h4>Appointment</h4>
		</div>
		<div class="form_cont">
			<form action="#" method="post">
				<div>
					<select id="sel_depart">
						<option selected>Select Department</option>
						<?php
						while($row=mysqli_fetch_assoc($result)){
						?>
						<option value="<?php echo $row['d_id'] ?>"><?php echo $row['d_name'] ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</form>
		</div>
		<div class="doctor_card_cont">
			<!-- <div class="doctor_card">
				<div>
					<img src="#">
				</div>
				<div>
					<h4>Doctor Name</h4>
					<h5>Degree</h5>
					<h5>speciality</h5>
					<h4>Timing</h4>
				</div>
				<div>
					<button>Book</button>
				</div>
			</div> -->
		</div>
	</div>
	<script type="text/javascript">
		
		$(document).ready(function(){



			$('#sel_depart').on("change",function(){
				var did=this.value;

				$(this).manager({
					url:"server/hospitalmanager.php",
					data: {
						did:did,
						action:'doc_search'
					},
					callbackfunc:function(data){
						data=JSON.parse(data);

						console.log(data);

						$('.doctor_card_cont>.doctor_card').detach();

						for(i in data['data']){
							var dimg=$("<img />",{
								"src":"#"
							});

							var dname=$("<h4></h4>",{
								"text":data['data'][i]['doc_name']
							});

							var degree=$("<h5></h5>",{
								"text":data['data'][i]['degree']
							});
							var spec=$("<h5></h5>",{
								"text":data['data'][i]['speciality']
							});

							var timing=$("<h4></h4>",{
								"text":data['data'][i]['slot_time']+" AM"
							});

							var bookbut=$("<button></button>",{
								"text":"Book",
								"class":"bookbut",
								"data-docid":data['data'][i]['doc_id'],
								click:function(){
									var doc_id=this.dataset.docid;

									if(his.islogin){
										window.parent.location="appointment.php?docid="+doc_id+"&hid="+his.hid;
									}
									else{
										window.parent.closeAndLogin();
									}
								}
							});

							var sec_a=$("<div></div>");
							var sec_b=$("<div></div>");
							var sec_c=$("<div></div>");

							sec_a.append(dimg);

							sec_b.append(dname);
							sec_b.append(degree);
							sec_b.append(spec);
							sec_b.append(timing);

							sec_c.append(bookbut);

							var card=$("<div></div>",{
								"class":"doctor_card"
							});

							card.append(sec_a);
							card.append(sec_b);
							card.append(sec_c);

							$('.doctor_card_cont').append(card);
						}
					}
				});
			});

		});
	</script>
</body>
</html>