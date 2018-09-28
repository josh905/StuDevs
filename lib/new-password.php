<?php

include_once 'config.php';

if(!isset($_POST['password'])){
	header("Location: ../index.php?go_to_profile_page_to_edit_profile");
	exit();
}

$password = $_POST['password'];
$repeatPassword = $_POST['repeatPassword'];

$table = $_SESSION['table'];


if(empty($password) || empty($repeatPassword)){
	header("Location: ../profile.php?edit_profile_error=empty_fields");
	exit();
}

if($password !== $repeatPassword){
	header("Location: ../profile.php?edit_profile_error=unmatching_passwords");
	exit();
}


if(strlen($password) < 6){
	header("Location: ../profile.php?edit_profile_error=password_too_short");
	exit();
}

if(strlen($password) > 8999){
	header("Location: ../profile.php?edit_profile_error=password_too_long");
	exit();
}

$password = password_hash($password, PASSWORD_DEFAULT);


$statement = $conn->prepare("UPDATE $table SET password=? WHERE acc_ref=?");

$statement->bind_param("sss", $passwordP, $accountRefP);

$passwordP = $password;
$accountRefP = $_SESSION['accountRef'];

$statement->execute();

$result = $conn->query($statement);
header("Location: ../profile.php?profile_updated_successfully");


