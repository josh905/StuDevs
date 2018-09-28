<?php

include_once 'lib/config.php';

//assigned projects
$statement = $conn->prepare("SELECT * FROM project WHERE project_id !=? AND accepted='1'");
$statement->bind_param("s", $projIdPrep);//this must be "s" !
$projIdPrep = '0';
$statement->execute();
$result = $statement->get_result();
$assignedProjectCount = mysqli_num_rows($result);

//available projects
$statement = $conn->prepare("SELECT * FROM project WHERE project_id !=? AND accepted='0'");
$statement->bind_param("s", $projIdPrep);//this must be "s" !
$projIdPrep = '0';
$statement->execute();
$result = $statement->get_result();
$availableProjectCount = mysqli_num_rows($result);



$statement = $conn->prepare("SELECT * FROM business WHERE business_id !=?");
$statement->bind_param("s", $busIdPrep);//this must be "s" !
$busIdPrep = '0';
$statement->execute();
$result = $statement->get_result();
$businessCount = mysqli_num_rows($result);


$statement = $conn->prepare("SELECT * FROM student WHERE student_id !=?");
$statement->bind_param("s", $stuIdPrep);//this must be "s" !
$stuIdPrep = '0';
$statement->execute();
$result = $statement->get_result();
$studentCount = mysqli_num_rows($result);

$statement = $conn->prepare("SELECT * FROM project WHERE project_id !=? AND accepted='0' ORDER BY RAND() LIMIT 3");
$statement->bind_param("s", $projIdPrep);//this must be "s" !
$projIdPrep = '0';
$statement->execute();
$result = $statement->get_result();

$bus = array();
$title = array();
$desc = array();
$req = array();
$recPrice = array();
$price = array();


while($row = $result->fetch_assoc()){
	array_push($bus, $row['business_name']);
	array_push($title, $row['title']);
	array_push($desc, $row['description']);
	array_push($req, $row['requirements']);
	array_push($recPrice, $row['rec_price']);
	array_push($price, $row['price']);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | Student Sofware Developers</title>
	<?php include_once 'lib/metalinks.php'; ?>
	
</head>
<body>
<div class="loader-bg"></div>
<div id="wrapper">

<!-- Page header -->	
	<header>
		<?php include_once 'lib/navbar.php'; ?>
    </header>

    <section class="no-padding adv-search-section">
		<!-- Slider main container -->
		<div id="swiper1" class="swiper-container">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<!-- Slides -->


					<!--start of project listing. -->
				<div class="swiper-slide">
					<div class="slide-bg swiper-lazy" data-background="images/coding1.jpg"></div>
					<!-- Preloader image -->
					<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-2 col-md-offset-4 col-lg-offset-6 slide-desc-col animated fadeInDown slide-desc-1">
								<div class="slide-desc pull-right">
									<div class="slide-desc-text">
										
										<div class="estate-type">Studevs Guideline</div>
										<div class="transaction-type">€<?php echo $recPrice[0]; ?></div>
										<h4><?php echo $title[0]; ?></h4>
										<div class="clearfix"></div>
										
										<p>
											<b>Business: </b><?php echo $bus[0]; ?><br>
											<b>Requirements: </b><?php echo $req[0]; ?><br>
											<b>Description: </b><?php echo $desc[0]; ?><br>
										</p>

									</div>
									
									<div class="slide-desc-price">
										€<?php echo $price[0]; ?>
									</div>
									<div class="clearfix"></div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				

				
				<div class="swiper-slide">
					<div class="slide-bg swiper-lazy" data-background="images/coding2.jpg"></div>
					<!-- Preloader image -->
					<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 slide-desc-col animated slide-desc-2">
								<div class="slide-desc pull-left">
									<div class="slide-desc-text">
										
										<div class="estate-type">Studevs Guideline</div>
										<div class="transaction-type">€<?php echo $recPrice[1]; ?></div>
										<h4><?php echo $title[1]; ?></h4>
										<div class="clearfix"></div>
										
										<p>
											<b>Business: </b><?php echo $bus[1]; ?><br>
											<b>Requirements: </b><?php echo $req[1]; ?><br>
											<b>Description: </b><?php echo $desc[1]; ?><br>
										</p>

									</div>
									
									<div class="slide-desc-price">
										€<?php echo $price[1]; ?>
									</div>
									<div class="clearfix"></div>										
								</div>
								
							</div>
						</div>
					</div>
					
				</div>


				<div class="swiper-slide">
					<div class="slide-bg swiper-lazy" data-background="images/coding3.jpg"></div>
					<!-- Preloader image -->
					<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-1 col-md-offset-2 col-lg-offset-3 slide-desc-col animated slide-desc-3">
								<div class="slide-desc center-block">
									<div class="slide-desc-text">
										
										<div class="estate-type">Studevs Guideline</div>
										<div class="transaction-type">€<?php echo $recPrice[2]; ?></div>
										<h4><?php echo $title[2]; ?></h4>
										<div class="clearfix"></div>
										
										<p>
											<b>Business: </b><?php echo $bus[2]; ?><br>
											<b>Requirements: </b><?php echo $req[2]; ?><br>
											<b>Description: </b><?php echo $desc[2]; ?><br>
										</p>

									</div>
							
									<div class="slide-desc-price">
										€<?php echo $price[2]; ?>
									</div>
									<div class="clearfix"></div>									
								</div>
								
							
							</div>
						</div>
					</div>
				</div>
		




				<!--end of project listing. -->


			</div>
		</div>
    </section>

    <section class="section-light top-padding-45 bottom-padding-45">
		<div class="container">
			<div class="row count-container">
				<div class="col-xs-6 col-lg-3">
					<div class="number" id="number1">
						<div class="number-img">	
							<i class="fa fa-group"></i>
						</div>
						<span class="number-label text-color2">Students</span>
						<span class="number-big text-color3 count" data-from="0" data-to="<?php echo $studentCount; ?>" data-speed="2000"></span>
					</div>
				</div>
				<div class="col-xs-6 col-lg-3 number_border">
					<div class="number" id="number2">
						<div class="number-img">	
							<i class="fa fa-credit-card"></i>	
						</div>			
						<span class="number-label text-color2">Businesses</span>
						<span class="number-big text-color3 count" data-from="0" data-to="<?php echo $businessCount; ?>" data-speed="2000"></span>
					</div>
				</div>
				<div class="col-xs-6 col-lg-3 number_border3">
					<div class="number" id="number3">
						<div class="number-img">	
							<i class="fa fa-laptop"></i>
						</div>
						<span class="number-label text-color2">Available Projects</span>
						<span class="number-big text-color3 count" data-from="0" data-to="<?php echo $availableProjectCount; ?>" data-speed="2000"></span>
					</div>
				</div>
				<div class="col-xs-6 col-lg-3 number_border4">
					<div class="number" id="number4">
						<div class="number-img">	
							<i class="fa fa-calendar-check-o"></i>
						</div>
						<span class="number-label text-color2">Assigned Projects</span>
						<span class="number-big text-color3 count" data-from="0" data-to="<?php echo $assignedProjectCount; ?>" data-speed="2000"></span>
					</div>
				</div>
			</div>
		</div>
	</section>
	
    <section class="section-light bottom-padding-45 section-both-shadow">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3">
					<div class="feature wow fadeInLeft" id="feature1">
						<div class="feature-icon center-block"><i class="fa fa-group"></i></div>
						<div class="feature-text">
							<h5 class="subtitle-margin"></h5>
							<h3>Students<span class="special-color">.</span></h3>
							<div class="title-separator center-block feature-separator"></div>
							<p>Students on studevs.com are currently enrolled in an Irish college or university.  They are verified students and often accept business' offers when the business follows the StuDevs guideline for price recommendations.</p>
						</div>
					</div>
				</div>			
				<div class="col-sm-6 col-lg-3">
					<div class="feature wow fadeInUp" id="feature2">
						<div class="feature-icon center-block"><i class="fa fa-credit-card"></i></div>
						<div class="feature-text">
							<h5 class="subtitle-margin"></h5>
							<h3>Businesses<span class="special-color">.</span></h3>
							<div class="title-separator center-block feature-separator"></div>
							<p>If you are a small business, StuDevs can help.  The price of development is often significantly more affordable on our site than elsewhere.</p>
						</div>
					</div>
				</div>			
				<div class="col-sm-6 col-lg-3">
					<div class="feature wow fadeInUp" id="feature3">
						<div class="feature-icon center-block"><i class="fa fa-laptop"></i></div>
						<div class="feature-text">
							<h5 class="subtitle-margin"></h5>
							<h3>Available Projects<span class="special-color">.</span></h3>
							<div class="title-separator center-block feature-separator"></div>
							<p>Business' software development projects can be anything from mobile apps to websites.  Available projects are those with no student currently working on them.</p>
						</div>
					</div>
				</div>	
				<div class="col-sm-6 col-lg-3">
					<div class="feature wow fadeInUp" id="feature4">
						<div class="feature-icon center-block"><i class="fa fa-calendar-check-o"></i></div>
						<div class="feature-text">
							<h5 class="subtitle-margin"></h5>
							<h3>Assigned Projects<span class="special-color">.</span></h3>
							<div class="title-separator center-block feature-separator"></div>
							<p>When a project gets underway, an estimated timeline is given to be followed.  Payment is given upon completion of the project.</p>
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