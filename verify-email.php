<?php 

include_once 'lib/config.php';

$rowCount = 0;


$urlArr = explode("?", $url);

if(empty($urlArr)){
	header("Location: index.php?error=invalid_verification_code");
	exit();
}

if(strlen($urlArr[0])<3){
	header("Location: index.php?error=invalid_verification_code");
	exit();
}

if(strlen($urlArr[1])<35){
	header("Location: index.php?error=invalid_verification_code");
	exit();
}


$verifCode = $urlArr[1];



$statement = $conn->prepare("UPDATE student SET verified=? WHERE verif_code=?");
$statement->bind_param("ss", $verifiedPrepS, $verifCodePrepS); //this must be "s" !
$verifiedPrepS = '1';
$verifCodePrepS = $verifCode;
$statement->execute();

$result = $conn->query($statement);

$rowCount += mysqli_num_rows($result);

$statement = $conn->prepare("UPDATE business SET verified=? WHERE verif_code=?");
$statement->bind_param("ss", $verifiedPrepB, $verifCodePrepB); //this must be "s" !
$verifiedPrepB = '1';
$verifCodePrepB = $verifCode;
$statement->execute();

$result = $conn->query($statement);



header("Location: index.php?email_verification_successful");
exit();


