<?php

include_once 'config.php';



if(!isset($_SESSION['studentId'])){
	header("Location: ../projects.php?error=please_log_in_as_a_student");
	exit();
}


$stuName = $_SESSION['fullName'];


$projIdReq = strtolower(end(explode('?', $url)));


$stuRef = $_SESSION['accountRef'];
$college = $_SESSION['college'];
$about = $_SESSION['about'];
$experience = $_SESSION['experience'];


$code = generateCode() . $stuRef;



$statement = $conn->prepare("SELECT * FROM project WHERE student_ref=?");
$statement->bind_param("s", $p7);
$p7= $stuRef;
$statement->execute();
$result = $statement->get_result();

$projCountStu = mysqli_num_rows($r1);

/*
if($projCountStu>0){
	header("Location: ../students.php?error=you_are_already_assigned_to_a_project");
	exit();
}
*/

$statement = $conn->prepare("SELECT * FROM project WHERE project_id=?");
$statement->bind_param("s", $projP1);
$projP1= $projIdReq;
$statement->execute();
$result = $statement->get_result();

$emailProj = "";
$busName = "";
$accepted = "";
$currCode = "";

while ($row = $result->fetch_assoc()){
	$emailProj = $row['bus_email'];
	$busName = $row['business_name'];
	$currCode = (string)$row['accept_code'];
	$accepted = $row['accepted'];
}

if(strpos($currCode, $stuRef)){		//must be student's ref for both files!
	header("Location: ../projects.php?error=you_have_already_requested_this_project");
	exit();
}
if($accepted=='1'){
	header("Location: ../projects.php?error=this_project_has_already_been_accepted");
	exit();
}



$statement = $conn->prepare("UPDATE project SET accept_code=? WHERE project_id=?");

$statement->bind_param("ss", $p1, $p2);

$p1 = $code;
$p2 = $projIdReq;


$statement->execute();

$result = $conn->query($statement);





$to = $emailProj;
$subject = $stuName." wants to develop your project";

$message = "Dear ".$busName.",\n\n".$stuName." from ".$college." would like to develop your project.\n\n".$stuName."'s details:\n\n".$about."\n\n".$experience."\n\nClick here to accept this student as your project's software developer:\nhttp://www.studevs.com/accept-request.php?".$code."\n\nRegards,\n\nThe StuDevs team";

// More headers
$headers .= 'From: <noreply@studevs.com>' . "\r\n";

if(mail($to,$subject,$message,$headers)){
	header("Location: ../index.php?project_requested_successfully");
}
else{
	header("Location: ../index.php?error=email_request_failure");
}

