<?php

include_once 'config.php';


if(isset($_POST['project-sort'])){
	if($_POST['project-sort']=='1'){
		header("Location: ../projects.php?price_lowest_first");
		exit();
	} 
	if($_POST['project-sort']=='2'){
		header("Location: ../projects.php?price_highest_first");
		exit();
	} 
	if($_POST['project-sort']=='3'){
		header("Location: ../projects.php?date_newest_first");
		exit();
	} 
	if($_POST['project-sort']=='4'){
		header("Location: ../projects.php?date_oldest_first");
		exit();
	}
}

if(isset($_POST['student-sort'])){
	if($_POST['student-sort']=='1') {
		header("Location: ../students.php?newest_first");
		exit();
	}
	if($_POST['student-sort']=='2') {
		header("Location: ../students.php?newest_last");
		exit();
	}
}

//if script reaches this point
header("location:javascript://history.go(-1)");

