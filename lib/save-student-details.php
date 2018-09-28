<?php

include_once 'config.php';

if(!isset($_POST['experience'])){
	header("Location: ../index.php?go_to_profile_page_to_edit_profile");
	exit();
}

$about = $_POST['about'];
$experience = $_POST['experience'];



if(empty($about) && empty($experience)){
	header("Location: ../profile.php?please_enter_details_to_save");
	exit();
}

if(empty($about)){
	$about = "No description given";
}

if(empty($experience)){
	$experience = "No experience given";
}

if(strlen($about) > 500){
	header("Location: ../profile.php?error=500_characters_max_for_about_me_field");
	exit();
}

if(strlen($experience) > 500){
	header("Location: ../profile.php?error=500_characters_max_for_my_experience_field");
	exit();
}



$statement = $conn->prepare("UPDATE student SET experience=?, about=? WHERE acc_ref=?");

$statement->bind_param("sss", $p1, $p2, $p3);

$p1 = $experience;
$p2 = $about;
$p3 = $_SESSION['accountRef'];

$statement->execute();

$result = $conn->query($statement);


header("Location: ../profile.php?profile_updated_successfully");


