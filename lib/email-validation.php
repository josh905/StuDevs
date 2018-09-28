<?php 

include_once 'config.php';

function validEmail($email){
	$regexp='/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
	return preg_match($regexp, trim($email));
}



if(!validEmail($email)){ //if email is invalid
	unset($_SESSION['email']);
	if(isset($_POST['busName'])) header("Location: ../index.php?business_sign_up_error=invalid_email");
	else header("Location: ../index.php?student_sign_up_error=invalid_email");
	exit();
}


if(strlen($email)>249){
	unset($_SESSION['email']);
	if(isset($_POST['busName'])) header("Location: ../index.php?business_sign_up_error=email_too_long");
	else header("Location: ../index.php?student_sign_up_error=email_too_long");
	exit();
}

//check is email already in the database

//prepared statement

$statement = $conn->prepare("SELECT email FROM student WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement

$statement->execute();

$result = $statement->get_result();

$emailCheck = $result->num_rows;


if($emailCheck > 0){
	unset($_SESSION['email']);
	if(isset($_POST['busName'])) header("Location: ../index.php?business_sign_up_error=email_already_in_use");
	else header("Location: ../index.php?student_sign_up_error=email_already_in_use");
	exit();
}



//prepared statement
$statement = $conn->prepare("SELECT email FROM business WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement

$statement->execute();

$result = $statement->get_result();

$emailCheck = $result->num_rows;

if($emailCheck > 0){
	unset($_SESSION['email']);
	if(isset($_POST['busName'])) header("Location: ../index.php?business_sign_up_error=email_already_in_use");
	else header("Location: ../index.php?student_sign_up_error=email_already_in_use");
	exit();
}



