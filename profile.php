<?php


include_once 'lib/config.php';

if($_SESSION['loggedIn']!==true){
	header("Location: index.php?log_in_or_sign_up_to_view_profile");
	exit();
}

//check if account verified every time any page reloads
if(isset($_SESSION['busId'])){
	//select verif code and verified status check if verified if not then redirect index.php?error=go_to_your_email_and_click_link_to_verify
	$statement = $conn->prepare("SELECT verified FROM business WHERE business_id=? AND verified=?");
	$statement->bind_param("ss", $busIdPrep, $verifiedPrep);
	$busIdPrep = $_SESSION['busId'];
	$verifiedPrep = '0';
	$statement->execute();
	$result = $statement->get_result();
	$zeroCount = mysqli_num_rows($result);
	if($zeroCount > 0){
		session_destroy();
		header("Location: index.php?please_verify_email_and_log_back_in");
		exit();
	}

	$busRef = $_SESSION['accountRef'];



	$statement = $conn->prepare("SELECT * FROM project WHERE business_ref=?");
	$statement->bind_param("s", $pq1);//this must be "s" !
	$pq1 = $busRef;
	$statement->execute();
	$result = $statement->get_result();
	$_SESSION['myProjCount'] = mysqli_num_rows($result);


}
if(isset($_SESSION['studentId'])){
	//select verif code and verified status check if verified if not then redirect index.php?error=go_to_your_email_and_click_link_to_verify
	$statement = $conn->prepare("SELECT verified FROM student WHERE student_id=? AND verified=?");
	$statement->bind_param("ss", $studentIdPrep, $verifiedPrep);
	$studentIdPrep = $_SESSION['studentId'];
	$verifiedPrep = '0';
	$statement->execute();
	$result = $statement->get_result();
	$zeroCount = mysqli_num_rows($result);
	if($zeroCount > 0){
		session_destroy();
		header("Location: index.php?please_verify_email_and_log_back_in");
		exit();
	}
}


$statement = $conn->prepare("SELECT * FROM session WHERE login_date=? AND acc_ref=?");
$statement->bind_param("ss", $loginDatePrep, $accountRefPrep);//this must be "s" !
$loginDatePrep = $_SESSION['loginDate'];
$accountRefPrep = $_SESSION['accountRef'];
//end of prepared statement
$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc();

$_SESSION['sessionId'] = $row['session_id'];

$_SESSION['table'] = strtolower($_SESSION['accountType']);

$table = $_SESSION['table'];

//selecting profile picture extension type eg png, jpg, jpeg
$statement = $conn->prepare("SELECT * FROM $table WHERE acc_ref=?");
$statement->bind_param("s", $accountRefPrep);//this must be "s" !
$accountRefPrep = $_SESSION['accountRef'];
$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc();
$_SESSION['picType'] = $row['pic_type'];




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | My Profile</title>
	<?php include_once 'lib/metalinks.php'; ?>
	
</head>
<body>
	
<div class="loader-bg"></div>
<div id="wrapper">

<!-- Page header -->	
	<header>
		<?php include_once 'lib/navbar.php'; ?>
    </header>
  		
    <section class="short-image no-padding blog-short-title">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-lg-12 short-image-title">
					<h5 class="black-text-pro subtitle-margin second-color">Dashboard</h5>
					<h1 class="black-text-pro second-color">
							<?php

							if(isset($_SESSION['busName'])) echo $_SESSION['busName']; 

							else echo $_SESSION['firstName']; 

							 ?>'s Profile</h1>
					<div class="short-title-separator"></div>
				</div>
			</div>
		</div>
    </section>
	
	<section class="section-light section-top-shadow">
		<div class="container">
			<div class="row">


		<?php if(strpos($url, "payment_transaction_successful")){ ?>
			<div class="col-xs-2"></div>
			<div class="col-xs-8">
				<h1>Transaction of €<?php echo $_SESSION['projPrice']; ?> successful.</h1>
				<br><br><br>
				<br><br>
			</div>
			<div class="col-xs-2"></div>
   		<?php  } ?>

				<div class="col-xs-12 col-md-9 col-md-push-3">
					<div class="row">
						<div class="col-xs-12">
							<h5 class="subtitle-margin">
							<?php

							if(isset($_SESSION['busName'])) echo $_SESSION['busName']; 

							else echo $_SESSION['firstName']; 

							 ?>'s</h5>

							<h1>Profile<span class="special-color">.</span></h1>
							<div class="title-separator-primary"></div>
						</div>
					</div>	

					<!-- Start of profile picture form -->
					<form name="profile-picture-form" method="post" action="lib/profile-picture.php" enctype="multipart/form-data">

						<div class="row margin-top-60">
						<div class="col-xs-6 col-xs-offset-3 col-sm-offset-0 col-sm-3 col-md-4">	
							<div class="agent-photos">

								<?php

							
								if($_SESSION['picType']=='0'){
									echo "<img src='images/profdefault.jpg' id='profile-photo' class='img-responsive' alt='Profile Picture' />";
								} 
								else{ 
									echo "<img id='profile-photo' class='img-responsive' 
									src='images/uploads/user".$_SESSION['accountRef'].".".$_SESSION['picType']."' alt='Profile Picture' />";
								}

								?>

								<div class="change-photo">


									  <i class="fa fa-pencil fa-lg"></i>
									<input type="file" name="profilePic" id="photo-file" onchange="document.getElementById('profile-photo').src = window.URL.createObjectURL(this.files[0])"/>

									
								</div>
								
								<input type="text" disabled="disabled" id="agent-file-name" class="main-input" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-9 col-md-8">
							<div class="labelled-input">
								<label for="full-name"><?php

							if(isset($_SESSION['busName'])) echo "Business Name";

							else echo "Full Name";

							 ?></label><input id="full-name" name="full-name" readonly type="text" class="input-full main-input" placeholder="" value="<?php echo $_SESSION['fullName']; ?>"/>
								<div class="clearfix"></div>
							</div>
							<div class="labelled-input">
								<label for="email">Email</label><input id="email" name="email" type="email" readonly class="input-full main-input" placeholder="" value="<?php echo $_SESSION['email']; ?>"/>
								<div class="clearfix"></div>
							</div>
							<div class="labelled-input">
								<label for="accountType">Account Type</label><input id="accountType" readonly name="accountType" type="text" class="input-full main-input" placeholder="" value="<?php echo $_SESSION['accountType']; ?>"/>
								<div class="clearfix"></div>
							</div>
						

							<div class="labelled-input">
								<label for="accountRef">Account Ref.</label><input id="accountRef" readonly name="accountRef" type="text" class="input-full main-input" placeholder="" value="<?php echo $_SESSION['accountRef']; ?>"/>
								<div class="clearfix"></div>
							</div>

							

						</div>
					</div>


					<div class="row margin-top-15">
						
						<div class="col-xs-4">
							<div class="center-button-cont margin-top-15">
								<button type="submit" class="button-primary button-shadow button-full">Save Profile Picture</button>
							</div>
							<div class="col-xs-8"></div>
						</div>
					</div>

				</form>

				<!-- End of profile picture form -->
				<div clas="row">
				<div class="col-xs-2"></div>
				<div class="col-xs-8">
					<?php 

							if(isset($_SESSION['studentId'])){
								echo "<form method='post' action='lib/save-student-details.php'><br><br><div class='labelled-input'>
											<label for='about' style='width: 100%;'><b>About me:</b></label><textarea id='about' name='about' type='text' class='input-full main-input' style='border-color: lightgray;'></textarea>
											<div class='clearfix'></div>
										</div><br><br>


										<div class='labelled-input'>
											<label for='about' style='width: 100%;'><b>My experience: </b></label><textarea id='experience' name='experience' type='text' class='input-full main-input' style='border-color: lightgray;'></textarea>
											<div class='clearfix'></div>
										</div>
										
										<div class='center-button-cont margin-top-15'>
											<button type='submit' style='width: 50%;' class='button-primary button-shadow button-full'>Save Details</button>
										</div>
										</form>

										";
							} 

							?>
				</div>
				<div class="col-xs-2"></div>
				</div>
				


				
								
						<div class="row">
							<br><br>
							<div class="col-xs-12 margin-top-60">
								<div class="info-box">
									<p>Only fill the fields below if you want to change your password</p>
									<div class="small-triangle"></div>
									<div class="small-icon"><i class="fa fa-info fa-lg"></i></div>
								</div>
							</div>
						</div>
				


					<!-- Start of new password form -->
					<form name="new-password-form" method="post" action="lib/new-password.php">

					<div class="row margin-top-15">
						<div class="col-xs-12 col-lg-6">
							<div class="labelled-input-short">
								<label for="first-name">New Password</label>
								<input id="password" name="password" type="password" class="input-full main-input" placeholder="" value=""/>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-xs-12 col-lg-6">
							<div class="labelled-input-short">
								<label for="first-name">Repeat Password</label>
								<input id="repeat-password" name="repeatPassword" type="password" class="input-full main-input" placeholder="" value=""/>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					

					<div class="row margin-top-15">
						<div class="col-xs-4"></div>
						<div class="col-xs-4">
							<div class="center-button-cont margin-top-15">
								<button type="submit" class="button-primary button-shadow button-full">Save New Password</button>
							</div>
						<div class="col-xs-4"></div>
						</div>
					</div>

					</form>
					<!-- End of new password form -->


					<div class="row margin-top-60"></div>
				</div>			
				<div class="col-xs-12 col-md-3 col-md-pull-9">
					<div class="sidebar-left">
						<h3 class="sidebar-title"><?php echo $_SESSION['fullName']; ?><span class="special-color">.</span></h3>
						<div class="title-separator-primary"></div>
						
						<div class="profile-info margin-top-60">
							<div class="profile-info-title negative-margin"><?php echo $_SESSION['fullName']; ?></div>

							<?php 
							if($_SESSION['picType']=='0'){
								echo "<img src='images/profdefault.jpg' id='profile-photo' class='img-responsive' alt='Profile Picture' />";
							} 
							else{ 
								echo "<img id='profile-photo' class='img-responsive' 
								src='images/uploads/user".$_SESSION['accountRef'].".".$_SESSION['picType']."' alt='Profile Picture' />";
							}
							?>


							<div class="profile-info-text pull-left">
								
								<p class="subtitle-margin"><br><br>Account</p>
								<p class="">Reference No.</p>
								<p class=""><?php echo $_SESSION['accountRef']; ?></p>
								<a href="lib/logout.php" class="logout-link margin-top-30"><i class="fa fa-lg fa-sign-out"></i>Logout</a><br><br>
							</div>
							<div class="clearfix"></div>
						</div>
						

						
						
						<?php if($_SESSION['accountType']=='Business'){ ?>
						<div class="center-button-cont margin-top-15">
							<a href="advertise.php" class="button-alternative button-shadow button-full">
								<span>Advertise Project</span>
								<div class="button-triangle"></div>
								<div class="button-triangle2"></div>
								<div class="button-icon"><i class="fa fa-laptop"></i></div>
							</a>
						</div>

						<div class="center-button-cont margin-top-15">
							<a href="students.php" class="button-primary button-shadow button-full">
								<span>Students</span>
								<div class="button-triangle"></div>
								<div class="button-triangle2"></div>
								<div class="button-icon"><i class="fa fa-group"></i></div>
							</a>
						</div>

						<div class="profile-info-text pull-left">
								<p class="subtitle-margin"><br><br>
							<b>Note:</b><br><br>StuDevs only allows 1 student to work on your project.<br><br>
							If you request a new student and the student accepts your request, we will cancel
							your agreement with the current student and you will receive <b>no software product</b>.
						</p>
							</div>
							<div class="clearfix"></div>

						
						<?php }

						else{ ?>
						<div class="center-button-cont margin-top-15">
							<a href="projects.php" class="button-alternative button-shadow button-full">
								<span>Projects</span>
								<div class="button-triangle"></div>
								<div class="button-triangle2"></div>
								<div class="button-icon"><i class="fa fa-laptop"></i></div>
							</a>
						</div>

						<div class="profile-info-text pull-left">
								<p class="subtitle-margin"><br><br>
							<b>Note:</b><br><br>StuDevs only allows you to work on 1 project at a time.<br><br>
							If you request a new project and the business accepts your request, we will cancel
							your agreement with the current business and you will receive <b>no payment</b>.
						</p>
							</div>
							<div class="clearfix"></div>
						
						<?php } ?>




					
				
					</div>
				</div>
			</div>
		</div>
	</section>

    <?php 
    include_once 'lib/footer.php'; 
    ?>




	</body>
</html>