<?php

include_once 'config.php';



include_once 'generate-refno.php';




$dateJoined = date('Y-m-d H:i:s');

$busName = $_POST['busName'];

$email = $_POST['email'];

$password = $_POST['password'];

$repeatPassword = $_POST['repeatPassword'];

$accountType = 'Business';


$_SESSION['busName'] = $busName;
$_SESSION['email'] = $email;
$_SESSION['password'] = $password;
$_SESSION['repeatPassword'] = $repeatPassword;
$_SESSION['accountType'] = $accountType;

$_SESSION['fullName'] = $busName;



if (empty($busName)||empty($password)||empty($email)||empty($repeatPassword)){
	header("Location: ../index.php?business_sign_up_error=empty_fields");
	exit();
}




if($password !== $repeatPassword){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?business_sign_up_error=unmatching_passwords");
	exit();
}



if((strlen($busName)<2)||(strlen($busName)>50)){
	unset($_SESSION['busName']);
	header("Location: ../index.php?business_sign_up_error=invalid_business_name_length");
	exit();
}


if (!preg_match("/^(?:[\s,.'-]*[a-zA-Z\pL][\s,.'-]*)+$/u", $busName)){
	unset($_SESSION['busName']);
	header("Location: ../index.php?business_sign_up_error=invalid_business_name_characters");
	exit();
}



include_once 'email-validation.php';


$verifCode = generateCode();




if(strlen($password) < 6){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?business_sign_up_error=password_too_short");
	exit();
}

if(strlen($password) > 8999){
	unset($_SESSION['password']);
	unset($_SESSION['repeatPassword']);
	header("Location: ../index.php?business_sign_up_error=password_too_long");
	exit();
}








/************ IF IT PASSES ALL ERRORS ABOVE, THEN PREPARE STATEMENT AND INSERT STATEMENT*****************/
trim($busName);
trim($email);

//one last check for empty fields because problems will occur if empty fields are inserted.
if (empty($password)||empty($busName)||empty($email)){
	header("Location: ../index.php?business_sign_up_error=empty_fields");
	exit();
}
if(empty($accountRef)||empty($dateJoined)||empty($accountType)){
	header("Location: ../index.php?business_sign_up_error=internal_sign_up_error");
	exit();
}



$password = password_hash($password, PASSWORD_DEFAULT);


$statement = $conn->prepare("INSERT INTO business (password, business_name, email, acc_ref, date_joined, verif_code) VALUES (?, ?, ?, ?, ?, ?)");



$statement->bind_param("ssssss", $passwordPrepared, $busNamePrepared, $emailPrepared, $accountRefPrepared, $dateJoinedPrepared, $verifCodePrepared);

$passwordPrepared = $password;
$busNamePrepared = $busName;
$emailPrepared = $email;
$accountRefPrepared = $accountRef;
$dateJoinedPrepared = $dateJoined;
$verifCodePrepared = $verifCode;

$statement->execute();

$result = $conn->query($statement);

$_SESSION['loggedIn'] = true;

//use an SQL update statement for last login datetime


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

