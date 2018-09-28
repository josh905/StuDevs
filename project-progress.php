<?php


include_once 'lib/config.php';

if($_SESSION['loggedIn']!==true){
	header("Location: index.php?please_log_in_or_sign_up");
	exit();
}

$stuRef = "";
$busRef = "";
$contactEmail = "";
$nameOfContact = "";

if(isset($_SESSION['busId'])){

	$busRef = $_SESSION['accountRef'];
	$stuRef = $_SESSION['stuRef'];

	if(strlen($stuRef)<7){
		header("Location: index.php?no_student_is_assigned_to_your_project");
		exit();
	}

}
if(isset($_SESSION['studentId'])){

	$stuRef = $_SESSION['accountRef'];
	$busRef = $_SESSION['busRef'];

	if(strlen($busRef)<7){
		header("Location: index.php?you_are_not_assigned_to_a_project");
		exit();
	}

}


$statement = $conn->prepare("SELECT * FROM project WHERE business_ref=? AND student_ref=?");
$statement->bind_param("ss", $p1, $p2);//this must be "s" !
$p1 = $busRef;
$p2 = $stuRef;
$statement->execute();
$result = $statement->get_result();


$stq = $conn->prepare("SELECT * FROM student WHERE acc_ref=?");
$stq->bind_param("s", $ap1);//this must be "s" !
$ap1 = $stuRef;
$stq->execute();
$restu = $stq->get_result();
$sturow = $restu->fetch_assoc();

$stuEmail = $sturow['email'];
$stuName = $sturow['first'] . " " . $sturow['last'];


$buq = $conn->prepare("SELECT * FROM business WHERE acc_ref=?");
$buq->bind_param("s", $ab1);//this must be "s" !
$ab1 = $busRef;
$buq->execute();
$resbu = $buq->get_result();
$busrow = $resbu->fetch_assoc();

$busEmail = $busrow['email'];
$busName = $busrow['business_name'];

if(isset($_SESSION['studentId'])){
	$contactEmail = $busEmail;
	$nameOfContact = $busName;
}

if(isset($_SESSION['busId'])){
	$contactEmail = $stuEmail;
	$nameOfContact = $stuName;
}




$projCount = mysqli_num_rows($result);

if($projCount<1){
	header("Location: index.php?you_do_not_have_an_project_in_progress");
	exit();
}


$reqArr = array();

$strReq = "";

$title = "";

$dateOfAgreement = "";

$email = "";

$row = $result->fetch_assoc();

$strReq = $row['requirements'];

$title = $row['title'];

$dateAdv = $row['date_advertised'];

$projId = $row['project_id'];

$price = $row['price'];

$recPrice = $row['rec_price'];

$dateAgreed = $row['date_of_agreement'];

$email = $row['bus_email'];

$title = $row['title'];

$_SESSION['projTitle'] = $title;

$_SESSION['projPrice'] = $price;

$_SESSION['projId'] = $projId;






$reqArr = explode(", ", $strReq);
														//planning and wireframes begin the DAY AFTER AGREEMENT
$projGanttString = "{ taskName: 'Software Planning & Wireframes', id: '1', start: projectAgreementDate + 1 * day, end: projectAgreementDate + 6 * day },";

$dependencyCount = 1;

/*
*
*
*	Used in_array() function because there is a specific order for which tasks are usually done first
*	so a for/foreach loop would be redundant.
*
*
*/



if(in_array('Display Information', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Display Information', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 3 * day, 
		end: projectAgreementDate + 40 * day },";
		$dependencyCount++;
}


if(in_array('Logo Design for Business', $reqArr)){
	
	$projGanttString .= "{ 
		taskName: 'Logo Design for Business', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 3 * day, 
		end: projectAgreementDate + 9 * day },";
		$dependencyCount++;
}


if(in_array('Contact Form', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Contact Form', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 4 * day, 
		end: projectAgreementDate + 10 * day },";
		$dependencyCount++;
}


if(in_array('Photo Gallery', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Photo Gallery', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 8 * day, 
		end: projectAgreementDate + 14 * day },";
		$dependencyCount++;
}


if(in_array('Login System', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Login System', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 10 * day, 
		end: projectAgreementDate + 26 * day },";
		$dependencyCount++;
}

if(in_array('Advertisements', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Advertisements', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 21 * day, 
		end: projectAgreementDate + 27 * day },";
		$dependencyCount++;
}

if(in_array('Map for Key Features', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Map for Key Features', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 18 * day, 
		end: projectAgreementDate + 30 * day },";
		$dependencyCount++;
}

if(in_array('ID/Verification', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'ID/Verification', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 10 * day, 
		end: projectAgreementDate + 22 * day },";
		$dependencyCount++;
}

if(in_array('Messaging System', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Messaging System', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 12 * day, 
		end: projectAgreementDate + 34 * day },";
		$dependencyCount++;
}

if(in_array('Site to User e-Commerce', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'Site to User e-Commerce', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 16 * day, 
		end: projectAgreementDate + 34 * day },";
		$dependencyCount++;
}

if(in_array('User to User e-Commerce', $reqArr)){
	$projGanttString .= "{ 
		taskName: 'User to User e-Commerce', 
		id: '".($dependencyCount+1)."', 
		dependency: '" . $dependencyCount . "', 
		start: projectAgreementDate + 18 * day, 
		end: projectAgreementDate + 43 * day },";
		$dependencyCount++;
}




$projGanttString .= "{ 
	taskName: 'Finalise Planning of Deployment/Software Transfer', 
	id: '".($dependencyCount+1)."', 
	dependency: '" . $dependencyCount . "', 
	start: projectAgreementDate + 30 * day, 
	end: projectAgreementDate + 36 * day },";
	$dependencyCount++;
	


$projGanttString .= "{ 
	taskName: 'Deployment/Software Transfer', 
	id: '".($dependencyCount+1)."', 
	dependency: '" . $dependencyCount . "', 
	start: projectAgreementDate + 45 * day, 
	end: projectAgreementDate + 48 * day },";
	$dependencyCount++;




$projGanttString .= "{ 
	taskName: 'Feedback and Project-Review Meeting', 
	id: '".($dependencyCount+1)."', 
	dependency: '" . $dependencyCount . "', 
	start: projectAgreementDate + 55 * day, 
	end: projectAgreementDate + 56 * day }";
		 			 //no ',' at the end ^ because it's always the last requirement
		  









?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | Project Progress</title>
	<?php include_once 'lib/metalinks.php'; ?>

	<!-- highcharts gantt chart JS link -->
	<script src="https://github.highcharts.com/gantt/highcharts-gantt.src.js"></script>

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
					<h5 class="black-text-pro subtitle-margin second-color"></h5>
					<h1 class="black-text-pro second-color">Project Progress</h1>
					<div class="short-title-separator"></div>
				</div>
			</div>
		</div>
    </section>
	
	<section class="section-light section-top-shadow">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">






					<div class="row">
						<div class="col-xs-12">
							<h5 class="subtitle-margin">Project:</h5>

							<h1><span class="special-color"><?php echo $title; ?></span></h1>
							<div class="title-separator-primary"></div>
						</div>
					</div>	

					<div class="row margin-top-30">
						
						<div class="col-xs-12"><br>
							<div class="labelled-input">
							<label for="fullName"><?php echo $nameOfContact.":"; ?></label><input id="fullName" readonly name="fullName" type="text" class="input-full main-input" placeholder="" value="<?php echo $contactEmail; ?>"/>
							<div class="clearfix"></div>
							</div>
						</div>
					
					</div>

					
						
						

					<div class="row margin-top-60">
						<div class="col-xs-12">

							<!-- JS below uses this div to display the HighChart Gantt -->
							 <div id="progress-container"></div>

						</div>
						<div class="col-xs-12">
							
						</div>
					</div>
					

					<div class="row margin-top-30"></div>


					<div class="row margin-top-60"></div>
				</div>			
				
			</div>





			<?php if(isset($_SESSION['busId'])){ ?>
			<div class="row">
				<div class="col-xs-4"></div>
				<div class="col-xs-4">
					
					<div class="center-button-cont margin-top-30">
						<a href="pay-student.php" 
							onclick="
							//if it has not yet reached deployment date
							if(currentStage < estDeployDate){
								if(confirm('We see that you have not yet reached the estimated deployment date.\nAre you sure you want to pay now?')){
									return true;
								}
								else{
									return false;
								}
							}
							//if it has reached deployment date
							else{
								if(confirm('Are you sure you want to pay now?')){
									return true;
								}
								else{
									return false;
								}
							}
							" 
							class="button-alternative button-shadow button-full">
							<span>Pay Student Now</span>
							<div class="button-triangle"></div>
							<div class="button-triangle2"></div>
							<div class="button-icon"><i class="fa fa-credit-card"></i></div>
						</a>
					</div>



				</div>
				<div class="col-xs-4"></div>
			</div>
			<?php } ?>




		</div>
	</section>
</div>

    <?php 
    include_once 'lib/footer.php'; 
    ?>



    <script type="text/javascript">

    	

    	var projectAgreementDate = new Date(<?php echo $dateOfAgreement; ?>),
   		day = 1000 * 60 * 60 * 24;

		//set time to 00:00:00:0000 because only date is relevant, not time.
		projectAgreementDate.setUTCHours(0);
		projectAgreementDate.setUTCMinutes(0);
		projectAgreementDate.setUTCSeconds(0);
		projectAgreementDate.setUTCMilliseconds(0);
		projectAgreementDate = projectAgreementDate.getTime();

		var estDeployDate = projectAgreementDate + 45 * day;


		//set current stage to today's date because it is an estimate of how far along the project should be
		var currentStage = new Date();
		
		//set time to 00:00:00:0000 because only date is relevant, not time.
		currentStage.setUTCHours(0);
		currentStage.setUTCMinutes(0);
		currentStage.setUTCSeconds(0);
		currentStage.setUTCMilliseconds(0);
		currentStage = currentStage.getTime();

		currentStage = currentStage + 2 * day; //set current stage equal to 2 days after itself * day
		//this gives the date of the current progress, so we can compare against the estDeployDate to see if it's pay time yet


		// THE CHART
		Highcharts.ganttChart('progress-container', {
		    title: {
		    	//make this $projName . " Timeline";
		        text: 'Timeline for <?php echo $title; ?>'
		    },
		    xAxis: {
		        currentDateIndicator: true,
		        min: projectAgreementDate - 3 * day,
		        max: projectAgreementDate + 60 * day
		    },

		    

		    series: [{
		        name: 'Project Tasks',
		        data: [


		        	//contains data from project table
					<?php echo $projGanttString; ?> 
		 
		        ]
		    }]
		});



    </script>



	</body>
</html>