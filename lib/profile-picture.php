<?php

include_once 'config.php';

if(!isset($_POST['full-name'])){
	header("Location: ../index.php?go_to_profile_page_to_edit_profile");
	exit();
}





$profilePic = $_FILES['profilePic'];
$table = $_SESSION['table'];

$name = $profilePic['name'];
$tempName = $profilePic['tmp_name'];
$size = $profilePic['size'];

if($size<1){
	header("Location: ../profile.php?edit_profile_error=no_image_selected"); 
	exit();
}

$error = $profilePic['error'];
$type = $profilePic['type'];

$ext = explode('.', $name);
$realExt = strtolower(end($ext));

$newName = "user".$_SESSION['accountRef'].".".$realExt;
$dest = '../images/uploads/'.$newName;




//this function is in main-functions.php
if(!isValidImage($profilePic)){
	header("Location: ../profile.php?edit_profile_error=invalid_image");
	exit();
}

//delete old pic
$filess = glob("../images/uploads/user".$_SESSION['accountRef'].".*");
foreach ($filess as $file_to_del) {
  unlink($file_to_del);
}



move_uploaded_file($tempName, $dest);


$statement = $conn->prepare("UPDATE $table SET pic_type=? WHERE acc_ref=?");

$statement->bind_param("ss", $picTypeP, $accountRefP);

$picTypeP = $realExt;
$accountRefP = $_SESSION['accountRef'];

$statement->execute();

$result = $conn->query($statement);
header("Location: ../profile.php?profile_updated_successfully");


