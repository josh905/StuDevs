<?php

include_once 'config.php';

function referenceLetters() {
    $characters = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomStrLet = '';
    for ($i = 0; $i < 3; $i++) {
        $randomStrLet .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomStrLet;
} 

function referenceNumbers() {
    $characters = '23456789';
    $charactersLength = strlen($characters);
    $randomStrNum = '';
    for ($i = 0; $i < 4; $i++) {
        $randomStrNum .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomStrNum;
}


$accountRef = "";

$refIsValid = false;


while(!$refIsValid){    //loop until it's a new reference number which isn't in the database.
                        //usually will only have to do it once but it's just in case.

	$refIsValid = true;

	$accountRef = referenceLetters() . referenceNumbers();

	$query = "SELECT acc_ref FROM student WHERE acc_ref='$accountRef'";
	$result = $conn->query($query);
	$accountRefCheck = mysqli_num_rows($result);

	if($accountRefCheck>0) $refIsValid = false;
	

	$query = "SELECT acc_ref FROM business WHERE acc_ref='$accountRef'";
	$result = $conn->query($query);
	$accountRefCheck = mysqli_num_rows($result);

	if($accountRefCheck>0) $refIsValid = false;
	
}

$_SESSION['accountRef'] = $accountRef;


