<?php

include_once 'config.php';



include_once 'generate-refno.php';


$dateJoined = date('Y-m-d H:i:s');

$firstName = $_POST['firstName'];

$lastName = $_POST['lastName'];

$email = $_POST['email'];

$password = $_POST['password'];

$repeatPassword = $_POST['repeatPassword'];

$accountType = 'Student';


$_SESSION['firstName'] = $firstName;
$_SESSION['lastName'] = $lastName;
$_SESSION['email'] = $email;
$_SESSION['password'] = $password;
$_SESSION['repeatPassword'] = $repeatPassword;
$_SESSION['accountType'] = $accountType;

$_SESSION['fullName'] = $firstName . " " . $lastName;




if (empty($password)||empty($firstName)||empty($lastName)||empty($email)||empty($repeatPassword)){
	header("Location: ../index.php?student_sign_up_error=empty_fields");
	exit();
}




if($password !== $repeatPassword){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?student_sign_up_error=unmatching_passwords");
	exit();
}




if((strlen($firstName)<2)||(strlen($firstName)>50)){
	unset($_SESSION['firstName']);
	header("Location: ../index.php?student_sign_up_error=invalid_first_name_length");
	exit();
}


if (!preg_match("/^(?:[\s,.'-]*[a-zA-Z\pL][\s,.'-]*)+$/u", $firstName)){
	unset($_SESSION['firstName']);
	header("Location: ../index.php?student_sign_up_error=invalid_first_name_characters");
	exit();
}


if((strlen($lastName)<2)||(strlen($lastName)>50)){
	unset($_SESSION['lastName']);
	header("Location: ../index.php?student_sign_up_error=invalid_last_name_length");
	exit();
}

if (!preg_match("/^(?:[\s,.'-]*[a-zA-Z\pL][\s,.'-]*)+$/u", $lastName)){
	unset($_SESSION['lastName']);
	header("Location: ../index.php?student_sign_up_error=invalid_last_name_characters");
	exit();
}


if(strlen($email)>249){
	unset($_SESSION['email']);
	header("Location: ../index.php?student_sign_up_error=email_too_long");
	exit();
}




include_once 'email-validation.php';

$verifCode = generateCode();




$_SESSION['college'] = setCollege($email);

if($_SESSION['college']=='None'){ //if email is not a student email address
	unset($_SESSION['email']);
	header("Location: ../index.php?student_sign_up_error=student_email_required");
	exit();
}



if(strlen($password) < 6){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?student_sign_up_error=password_too_short");
	exit();
}

if(strlen($password) > 8999){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?student_sign_up_error=password_too_long");
	exit();
}








/************ IF IT PASSES ALL ERRORS ABOVE, THEN DO THIS*****************/
trim($firstName);
trim($lastName);
trim($email);

//one last check for empty fields because major problems will occur if empty fields are inserted.
if (empty($password)||empty($firstName)||empty($lastName)||empty($email)){
	header("Location: ../index.php?student_sign_up_error=empty_fields");
	exit();
}
if(empty($accountRef)||empty($dateJoined)||empty($accountType)){
	header("Location: ../index.php?student_sign_up_error=internal_sign_up_error");
	exit();
}



$password = password_hash($password, PASSWORD_DEFAULT);



$statement = $conn->prepare("INSERT INTO student (password, first, last, email, acc_ref, date_joined, college, verif_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");



$statement->bind_param("ssssssss", $passwordPrepared, $firstNamePrepared, $lastNamePrepared, $emailPrepared, $accountRefPrepared, $dateJoinedPrepared, $collegePrepared, $verifCodePrepared);

$passwordPrepared = $password;
$firstNamePrepared = $firstName;
$lastNamePrepared = $lastName;
$emailPrepared = $email;
$accountRefPrepared = $accountRef;
$dateJoinedPrepared = $dateJoined;
$collegePrepared = $_SESSION['college'];
$verifCodePrepared = $verifCode;


$statement->execute();

$result = $conn->query($statement);

$_SESSION['loggedIn'] = true;

//use an SQL update statement for last login datetime

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
$_SESSION['accountRef'] = $row['acc_ref'];
$_SESSION['dateJoined'] = $row['date_joined'];
$_SESSION['busRef'] = $row['bus_proj'];
$_SESSION['about'] = $row['about'];
$_SESSION['experience'] = $row['experience'];
$_SESSION['accountType'] = 'Student';
$_SESSION['business'] = false;
$_SESSION['student'] = true;


$statement = $conn->prepare("INSERT INTO session (acc_ref, login_date, acc_type) VALUES (?, ?, ?)");



$statement->bind_param("sss", $accountRefP, $dateJoinedP, $accountTypeP);

$accountRefP = $accountRef;
$dateJoinedP= $dateJoined;
$accountTypeP = $_SESSION['accountType'];

$statement->execute();

$result = $conn->query($statement);


$_SESSION['loginDate'] = $dateJoined;



$_SESSION['loggedIn'] = true;

include_once 'send-email-verify.php';

$emailPass = trim($email);
$namePass = trim($_SESSION['fullName']);
$codePass = $verifCode;


$isSent = sendEmailVer($namePass, $emailPass, $codePass);

if($isSent){
	header("Location: ../profile.php?please_verify_email");
	exit();
} 
else{
	header("Location: ../profile.php?error=email_verification_did_not_send");
	exit();
}

