<?php

include_once 'config.php';



if(!isset($_SESSION['busId'])){
	header("Location: ../students.php?error=please_log_in_as_a_business");
	exit();
}

$busRef = $_SESSION['accountRef'];
$busName = $_SESSION['fullName'];

$stuIdReq = strtolower(end(explode('?', $url)));

$projId = "";
$title= "";
$desc = "";
$req = "";
$recPrice = "";
$price = "";
$accepted = "";
$currCode = "";

$s1 = $conn->prepare("SELECT * FROM project WHERE business_ref=?");
$s1->bind_param("s", $p1);//this must be "s" !
$p1 = $busRef;
$s1->execute();
$r1 = $s1->get_result();
$projCountBus = mysqli_num_rows($r1);

if($projCountBus<1){
	header("Location: ../students.php?error=you_dont_have_a_project_uploaded");
	exit();
}




while ($row = $r1->fetch_assoc()){
	$projId = $row['project_id'];
	$title= $row['title'];
	$desc = $row['description'];
	$req = $row['requirements'];
	$recPrice = $row['rec_price']; //need to cast(string)  ?
	$price = $row['price'];
	$accepted = $row['accepted'];
	$currCode = (string)$row['accept_code'];
}


/*
if($accepted=='1'){
	header("Location: ../students.php?error=there_is_already_a_student_assigned_to_your_project");
	exit();
}
*/

$s2 = $conn->prepare("SELECT * FROM student WHERE student_id=?");
$s2->bind_param("s", $p2);//this must be "s" !
$p2 = $stuIdReq;
$s2->execute();
$r2 = $s2->get_result();



$stuEmail = "";
$stuName = "";
$stuRef = "";

while ($row = $r2->fetch_assoc()){
	$stuEmail = $row['email'];
	$stuName = $row['first'];
	$stuRef = $row['acc_ref'];
}

if(strpos($currCode, $stuRef)){		//must be student's ref for both files!
	header("Location: ../students.php?error=you_have_already_requested_this_student");
	exit();
}

$code = generateCode() . $stuRef;



$s3 = $conn->prepare("UPDATE project SET accept_code=? WHERE project_id=?");

$s3->bind_param("ss", $p3, $p4);

$p3 = $code;
$p4 = $projId;


$s3->execute();

$r3 = $conn->query($s3);


$to = $stuEmail;
$subject = $busName." would like you to develop their project";

$message = "Hi ".$stuName.",\n\n".$busName." would like you to develop their project.\n\nProject details:\n\nProject title: ".$title."\n\nDescription: ".$desc."\n\nRequirements: ".$req."\n\nStuDevs guideline price: ".$recPrice."\n\nActual price: ".$price."\n\nClick here to accept this as your next software development project:\nhttp://www.studevs.com/accept-request.php?".$code."\n\nRegards,\n\nThe StuDevs team";

// More headers
$headers .= 'From: <noreply@studevs.com>' . "\r\n";

if(mail($to,$subject,$message,$headers)){
	header("Location: ../index.php?student_requested_successfully");
}
else{
	header("Location: ../index.php?error=email_request_failure");
}

