<?php

include_once 'lib/config.php';

$query = "";

$querystring = "";

if(strpos($url, 'newest_last')){
	$statement = $conn->prepare("SELECT * FROM student WHERE student_id!=? AND bus_proj IS NULL ORDER BY date_joined ASC");
	$statement->bind_param("s", $stuIdPrep);//this must be "s" !
	$stuIdPrep = '0';
}


else{
	$statement = $conn->prepare("SELECT * FROM student WHERE student_id!=? AND bus_proj IS NULL ORDER BY date_joined DESC");
	$statement->bind_param("s", $stuIdPrep);//this must be "s" !
	$stuIdPrep = '0';
}





$statement->execute();
$result = $statement->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | Students</title>
	<?php include_once 'lib/metalinks.php'; ?>
	
</head>
<body>
<div class="loader-bg"></div>
<div id="wrapper">
<!-- Page header -->	
	<header>
		<?php include_once 'lib/navbar.php';

		 ?>
    </header>
	


	<section class="section-light section-top-shadow">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-12 col-lg-6">
								<h5 class="subtitle-margin">Students available for work</h5>
								<h1><?php echo mysqli_num_rows($result);?> students found<span class="special-color">.</span></h1>

								<?php if(isset($_SESSION['busId'])) { ?>
								<p><br><br>
									<b>Note:</b><br>StuDevs only allows 1 student to work on your project.<br>
									If you request a new student and the student accepts your request, we will cancel
									your agreement with the current student and you will receive <b>no software product</b>.<br><br>
									Student's names and profile pictures are not displayed for privacy reasons.
								</p>
								<?php } ?>

							</div>
							<div class="col-xs-12 col-lg-6">											
								<div class="order-by-container">
									<form action="lib/sort-results.php" method="post">
									<select class="bootstrap-select" name="student-sort" title="Sort By:">
										<option value="1">Date joined: Newest <b>first</b></option>
										<option value="2">Date joined: Newest <b>last</b></option>
									</select>
									<button style="margin-left: 80%;" class="btn btn-primary" type="submit">Sort</button>
									</form>
								</div>	
							</div>							
							<div class="col-xs-12">
								<div class="title-separator-primary"></div>
							</div>
						</div>
						<div class="row list-offer-row">
							<div class="col-xs-12">




								<?php 
								while ($row = $result->fetch_assoc())  
								{
									$stuRequestId = $row['student_id'];
								?>

							
								<div class="list-offer">
									<div class="list-offer-left">
										<div class="list-offer-front">
									
											<div class="list-offer-photo">
												<?php 
													echo "<img src='images/uploads/profdefault.jpg' alt='' />";
												
												?>
												
											</div>
											
										</div>
										<div class="list-offer-back">
											<div id="list-map1" class="list-offer-map"></div>
										</div>
									</div>
									<?php $querystring = "/lib/request-student.php?" . (string)$row['student_id']; 

									if(isset($_SESSION['busId'])){ ?>
									<a class="list-offer-right-large" onclick="if(confirm('Request for this student to develop your project?')) return true; else return false;" href="<?php echo $querystring; ?>">
										<?php }
										elseif(isset($_SESSION['studentId'])){ ?>
											<a class="list-offer-right-large" onclick="alert('Only businesses can request for students to develop their projects'); return false;" href="<?php echo $querystring; ?>">
										<?php }
										 else{ ?>
											<a class="list-offer-right-large" href="#" onclick="showlogmod()">
										<?php } ?>

										<div class="list-offer-text">
											<i class="fa fa-handshake-o list-offer-localization hidden-xs"></i>
											<p><b>Student Reference Number: </b><?php echo $row['acc_ref']; ?></p>
											<?php echo "<i class='fa fa-user list-offer-localization hidden-xs'></i>"; ?>
											
											<div class="list-offer-h4"><h4 class="list-offer-title">
												<b>College/University: </b><?php echo $row['college']; ?></h4>
											</div>
											<div class="clearfix"></div>

											<?php 
											echo "<b>About student:</b> ".$row['about']."<br><br><b>Experience: </b> ".$row['experience']."<br><br><b>Date joined: </b>".date('j F Y',strtotime($row['date_joined']));
											?>
											<?php if(isset($_SESSION['busId'])){ ?>
											<p style="font-size: 130%"><br><b>Click to request student</b></p>
											<?php } ?>
											<p class="invisible-line"><?php echo $studesc; ?></p>
											<div class="clearfix"></div>
										</div>
										<div class="price-list-cont">
											
											
										</div>
									</a>
									<div class="clearfix"></div>
								</div>
								

								   <?php
								}

								?>






				
								
								
							</div>
						</div>
						
						
				</div>
			</div>
		</div>
	</section>

    <?php 
    include_once 'lib/footer.php'; 
    ?>


	</body>
</html>