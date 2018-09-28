<?php

//dont forget to include last login

include_once 'config.php';


$email = strtolower($_POST['loginEmail']);
$password = $_POST['loginPassword'];




$_SESSION['loginEmail'] = $email;



$loginDate = date('Y-m-d H:i:s');





//prepared statement
$statement = $conn->prepare("SELECT * FROM student WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement

$statement->execute();

$result = $statement->get_result();
$row = $result->fetch_assoc();

$studentEmailCheck = $result->num_rows;



//prepared statement
$statement = $conn->prepare("SELECT * FROM business WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement

$statement->execute();

$result = $statement->get_result();
$row = $result->fetch_assoc();

$businessEmailCheck = $result->num_rows;

if(($studentEmailCheck<1) && ($businessEmailCheck<1)){
	unset($_SESSION['loginEmail']);
	unset($_SESSION['loginPassword']);
	header("Location: ../index.php?log_in_error=invalid_email");
	exit();
}



$validType = 'none';


//determing whether student or business and checking password matches database password

$statement = $conn->prepare("SELECT * FROM student WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement
$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc();


if(password_verify($password, $row['password'])) $validType = 'student';

$statement = $conn->prepare("SELECT * FROM business WHERE email=?");
$statement->bind_param("s", $emailPrepared);//this must be "s" !
$emailPrepared = $email;
//end of prepared statement
$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc();


if(password_verify($password, $row['password'])) $validType = 'business';



if($validType=='none'){
	unset($_SESSION['loginPassword']);
	header("Location: ../index.php?log_in_error=invalid_password");
	exit();
}




if($validType=='student'){
	$statement = $conn->prepare("SELECT * FROM student WHERE email=?");
	$statement->bind_param("s", $emailPrepared);//this must be "s" !
	$emailPrepared = $email;
	//end of prepared statement
	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_assoc();

	$_SESSION['studentId'] = $row['student_id'];
	$_SESSION['firstName'] = $row['first'];
	$_SESSION['lastName'] = $row['last'];
	$_SESSION['fullName'] = $row['first'] . " " . $row['last'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['about'] = $row['about'];
	$_SESSION['experience'] = $row['experience'];
	$_SESSION['accountRef'] = $row['acc_ref'];
	$_SESSION['dateJoined'] = $row['date_joined'];
	$_SESSION['busRef'] = $row['bus_proj'];
	$_SESSION['college'] = $row['college'];
	$_SESSION['accountType'] = 'Student';
	$_SESSION['student'] = true;
	$_SESSION['business'] = false;
}



if($validType=='business'){
	$statement = $conn->prepare("SELECT * FROM business WHERE email=?");
	$statement->bind_param("s", $emailPrepared);//this must be "s" !
	$emailPrepared = $email;
	//end of prepared statement
	$statement->execute();
	$result = $statement->get_result();
	$row = $result->fetch_assoc();

	$_SESSION['busId'] = $row['business_id'];
	$_SESSION['busName'] = $row['business_name'];
	$_SESSION['fullName'] = $row['business_name'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['accountRef'] = $row['acc_ref'];
	$_SESSION['dateJoined'] = $row['date_joined'];
	$_SESSION['stuRef'] = $row['stu_proj'];
	$_SESSION['accountType'] = 'Business';
	$_SESSION['business'] = true;
	$_SESSION['student'] = false;
}




$statement = $conn->prepare("INSERT INTO session (acc_ref, login_date, acc_type) VALUES (?, ?, ?)");



$statement->bind_param("sss", $accountRefP, $dateJoinedP, $accountTypeP);

$accountRefP = $accountRef;
$dateJoinedP= $dateJoined;
$accountTypeP = $_SESSION['accountType'];

$statement->execute();

$result = $conn->query($statement);


$_SESSION['loginDate'] = $dateJoined;



$_SESSION['loggedIn'] = true;

header("Location: ../profile.php?welcome_back");


