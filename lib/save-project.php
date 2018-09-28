<?php

include_once 'config.php';

if(!isset($_SESSION['busId'])){
	header("Location: ../index.php?log_in_as_business");
	exit();
}
if(!isset($_POST['description'])){
	header("Location: ../profile.php?go_to_advertise_page");
	exit();
}


$busRef = $_SESSION['accountRef'];



$statement = $conn->prepare("SELECT * FROM project WHERE business_ref=?");
$statement->bind_param("s", $pq1);//this must be "s" !
$pq1 = $busRef;
$statement->execute();
$result = $statement->get_result();
$bProj = mysqli_num_rows($result);

if($bProj>0){
	header("Location: ../index.php?you_have_already_uploaded_a_project");
	exit();
}


$title = $_POST['title'];
$description = $_POST['description'];

//these 2 must be together to work, milliseconds in the difference will throw it off
$date = date('Y-m-d H:i:s');
$stamp = strtotime($date);
//these 2 must be together to work, milliseconds in the difference will throw it off


$projectPic = $_FILES['projectPic'];
$price = $_POST['myPrice'];
$recPrice = 180;




$requirements = $_POST['requirements'];


if(strlen($_POST['description'])<10){
	header("Location: ../advertise.php?description_must_be_10_characters_minimum");
	exit();
}

if(strlen($_POST['title'])<6){
	header("Location: ../advertise.php?title_must_be_6_characters_minimum");
	exit();
}

if(strlen($_POST['description'])>=500){
	header("Location: ../advertise.php?description_must_be_less_than_500_characters");
	exit();
}

if(strlen($_POST['title'])>=100){
	header("Location: ../advertise.php?title_must_be_less_than_100_characters");
	exit();
}

if(empty($price)){
	header("Location: ../advertise.php?please_enter_a_price");
	exit();
}

if(!is_numeric($price)){
	header("Location: ../advertise.php?please_enter_only_numbers_for_price");
	exit();
}


$strReq = "";

if(!empty($requirements)){
	for($i=0; $i<sizeof($requirements); $i++){
		$strReq.=$requirements[$i].", ";
	}
}




//setting requirements' values

			//4
if(in_array('Login System', $requirements)) $recPrice+=100; 


			//3
if(in_array('Photo Gallery', $requirements)) $recPrice+=60; 


			//2
if(in_array('Contact Form', $requirements)) $recPrice+=30; 


			//9
if(in_array('Messaging System', $requirements)) $recPrice+=115;	


			//8
if(in_array('ID/Verification', $requirements)) $recPrice+=80;


			//1
if(in_array('Logo Design for Business', $requirements)) $recPrice+=40;


			//11
if(in_array('User to User e-Commerce', $requirements)) $recPrice+=465;


			//10
if(in_array('Site to User e-Commerce', $requirements)) $recPrice+=320;


			//5
if(in_array('Advertisements', $requirements)) $recPrice+=210;


			//6
if(in_array('Map for Business Location', $requirements)) $recPrice+=25;


			//7
if(in_array('Map for Key Features', $requirements)) $recPrice+=240;






$name = $projectPic['name'];
$tempName = $projectPic['tmp_name'];
$size = $projectPic['size'];
$error = $projectPic['error'];
$type = $projectPic['type'];

$ext = explode('.', $name);
$realExt = strtolower(end($ext));

$newName = "project".$stamp.".".$realExt;
$dest = '../images/uploads/'.$newName;



if($size<1){
	$realExt = '0';
}
else{
	//isValidImage from mainfunctions php file
	if(!isValidImage($projectPic)){
		header("Location: ../advertise.php?invalid_project_image");
		exit();
	}
	move_uploaded_file($tempName, $dest);
}







$statement = $conn->prepare("INSERT INTO project (business_ref, business_name, bus_email, title, description, requirements, rec_price, price, date_advertised, pic_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");



$statement->bind_param("ssssssssss", $refPrep, $namePrep, $busEmailPrep, $titlePrep, $descPrep, $reqPrep, $recPricePrep, $pricePrep, $datePrep, $picPrep);

$refPrep = $_SESSION['accountRef'];
$namePrep = $_SESSION['busName'];
$busEmailPrep = $_SESSION['email'];
$titlePrep = $title;
$descPrep = $description;
$reqPrep = $strReq;
$recPricePrep = $recPrice;
$pricePrep = $price;
$datePrep = $date;
$picPrep = $realExt;

$statement->execute();

$result = $conn->query($statement);

header("Location: ../profile.php?project_ad_submitted_successfully");



