<?php 

include_once 'lib/config.php';



$urlArr = explode("?", $url);



if(empty($urlArr)){
	header("Location: index.php?error=invalid_acceptance_code");
	exit();
}

if(strlen($urlArr[0])<3){
	header("Location: index.php?error=invalid_acceptance_code");
	exit();
}

if(strlen($urlArr[1])<35){
	header("Location: index.php?error=invalid_acceptance_code");
	exit();
}


$acceptCode = $urlArr[1];

$stuRef = "";

//start reading at 41st value in refnum so $i starts at 40
for($i=40; $i<strlen($acceptCode); $i++){
	$stuRef.=$acceptCode[$i];
}


$s1 = $conn->prepare("UPDATE project SET accepted=? WHERE accept_code=?");
$s1->bind_param("ss", $p1, $p2); //this must be "s" !
$p1 = '1';
$p2 = $acceptCode;
$s1->execute();

$r1 = $conn->query($s1);


$s2 = $conn->prepare("UPDATE project SET student_ref=? WHERE accept_code=?");
$s2->bind_param("ss", $p3, $p4); //this must be "s" !
$p3 = $stuRef;
$p4 = $acceptCode;
$s2->execute();

$r2 = $conn->query($s2);

$statement = $conn->prepare("SELECT * FROM project WHERE student_ref=?");
$statement->bind_param("s", $refPrep);//this must be "s" !
$refPrep = $stuRef;
$statement->execute();
$result = $statement->get_result();
$busRef = "";

while($row = $result->fetch_assoc()){
	$busRef = $row['business_ref'];
}

$st = $conn->prepare("UPDATE student SET bus_proj=? WHERE acc_ref=?");
$st->bind_param("ss", $p1, $p2); //this must be "s" !
$p1 = $busRef;
$p2 = $stuRef;
$st->execute();
$res = $conn->query($st);

$st = $conn->prepare("UPDATE business SET stu_proj=? WHERE acc_ref=?");
$st->bind_param("ss", $p1, $p2); //this must be "s" !
$p1 = $stuRef;
$p2 = $busRef;
$st->execute();
$res = $conn->query($st);

$dateAccepted = date('Y-m-d H:i:s');

$st = $conn->prepare("UPDATE project SET date_of_agreement=? WHERE accept_code=?");
$st->bind_param("ss", $p1, $p2); //this must be "s" !
$p1 = $dateAccepted;
$p2 = $acceptCode;
$st->execute();
$res = $conn->query($st);

if(isset($_SESSION['studentId'])){
	$_SESSION['busRef'] = $busRef;
}
if(isset($_SESSION['busId'])){
	$_SESSION['stuRef'] = $stuRef;
}


header("Location: index.php?acceptance_successful");
exit();

