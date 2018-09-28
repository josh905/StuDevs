<?php

include_once 'lib/config.php';

if($_SESSION['business']!==true){
	header("Location: index.php?log_in_or_sign_up_as_business_to_advertise");
	exit();
}

if(isset($_SESSION['busId'])){
	if($_SESSION['myProjCount'] > 0){
		header("Location: index.php?you_already_have_a_project");
		exit();
	}
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | Advertise Project</title>
	<?php include_once 'lib/metalinks.php'; ?>
	
</head>
<body>
<div class="loader-bg"></div>
<div id="wrapper">

<!-- Page header -->	
	<header>
		<?php include_once 'lib/navbar.php'; ?>
    </header>
	
	
  	
    <section class="short-image no-padding blog-short-title">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-lg-12 short-image-title">
					<h5 class="black-text-pro subtitle-margin second-color">Advertise Project</h5>
					<h1 class="black-text-pro second-color">Advertise Project</h1>
					<div class="short-title-separator"></div>
				</div>
			</div>
		</div>
    </section>
	
	<section class="section-light section-top-shadow">
		<form action="lib/save-project.php" onsubmit="return priceCompare()" method="post" name="advertise-project" enctype="multipart/form-data">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="title-negative-margin">Project details<span class="special-color">.</span></h3>
						<div class="title-separator-primary"></div>
						<div class="dark-col margin-top-60">
							<div class="row">
							
								
								
								<div class="col-xs-12 col-sm-6 margin-top-15">
									<input required="" name="title" type="text" class="input-full main-input" placeholder="Project Title" />
								</div>

								
							
							</div>
							<textarea required="" name="description" class="input-full main-input property-textarea" placeholder="Description"></textarea>


					
							

							<div class="row margin-top-30">
							<h4>Choose Project Requirements</h4>

								<div class="col-xs-12 col-sm-4 col-md-6 col-lg-4 margin-top-15"> <!-- ONLY PUT 6 Qs PER DIV -->


									<input onchange="calcPrice(this, 100)" type="checkbox" id="c1" name="requirements[]" 
									class="main-checkbox" value="Login System"/>
									<label for="c1"><span></span>Users need to be able to log in</label><br/>

									<input onchange="calcPrice(this, 60)" type="checkbox" id="c2" 
									name="requirements[]" class="main-checkbox" value="Photo Gallery"/>
									<label for="c2"><span></span>Users need a photo gallery</label><br/>

									<input onchange="calcPrice(this, 30)" type="checkbox" id="c3" 
									name="requirements[]" class="main-checkbox" value="Contact Form"/>
									<label for="c3"><span></span>Users can contact me via the site</label><br/>

									<input onchange="calcPrice(this, 115)" type="checkbox" id="c4" 
									name="requirements[]" class="main-checkbox" value="Messaging System"/>
									<label for="c4"><span></span>Users can communicate with each other</label><br/>

									<input onchange="calcPrice(this, 80)" type="checkbox" id="c5" 
									name="requirements[]n" class="main-checkbox" value="ID/Verification"/>
									<label for="c5"><span></span>Users need to provide ID/verification</label><br/>

									<input onchange="calcPrice(this, 40)" type="checkbox" id="c6" name="requirements[]" 
									class="main-checkbox" value="Logo Design for Business"/>
									<label for="c6"><span></span>I want a logo designed and created</label><br/>

								</div>


								<div class="col-xs-12 col-sm-4 col-md-6 col-lg-4 margin-top-15"> <!-- ONLY PUT 6 Qs PER DIV -->

									<input onchange="calcPrice(this, 465)" type="checkbox" id="c7" 
									name="requirements[]" class="main-checkbox" value="User to User e-Commerce"/>
									<label for="c7"><span></span>Users will be buying/selling things to/from each other</label><br/>

									<input onchange="calcPrice(this, 320)" type="checkbox" id="c8" 
									name="requirements[]" class="main-checkbox" value="Site to User e-Commerce"/>
									<label for="c8"><span></span>Users will be buying things from my site (website functioning as a shop)</label><br/>

									<input onchange="calcPrice(this, 210)" type="checkbox" id="c9" 
									name="requirements[]" class="main-checkbox" value="Advertisements"/>
									<label for="c9"><span></span>Users will be advertising but making their own purchase arrangements.</label><br/>

									<input onchange="calcPrice(this, 25)" type="checkbox" id="c10" 
									name="requirements[]" class="main-checkbox" value="Map for Business Location"/>
									<label for="c10"><span></span>Map to show my business's location</label><br/>

									<input onchange="calcPrice(this, 240)" type="checkbox" id="c11" 
									name="requirements[]" class="main-checkbox" value="Map for Key Features"/>
									<label for="c11"><span></span>Map to show key features (eg: properties, user locations, item locations, etc.)</label><br/>


								</div>
					
							
							</div>



							<div class="row margin-top-30">
								<div class="col-xs-7">
									<label for="recPrice" class="adv-search-label">Recommended price (from requirements):<br><br><h3><span class="special-color" id="recPrice">€180</span></h3></label>

									
								</div>

								<div class="col-xs-5">
									<label for="myPrice" class="adv-search-label">My price (in Euro):</label>
									<input name="myPrice" id="myPrice" min="100" max="10000" type="number" class="input-full main-input" placeholder="eg: 380" required="" />
								</div>
							</div>


						</div>				
					</div>
					<div class="row">

						<div class="col-xs-3"></div>
					
					<div class="col-xs-6 margin-top-60">
					<h2 class="project-heady">Upload an image of how you want the site/app to look. <span class="special-color">(Optional)</span></h2>	
							<div class="margin-top-15 agent-photos"> 

								<?php

							
								
									echo "<img src='images/project-default.PNG' id='project-photo' class='img-responsive' alt='Project Picture' />";
								
									
								

								?>

								<div class="change-photo">


									  <i class="fa fa-pencil fa-lg"></i>
									<input type="file" name="projectPic" id="photo-file" onchange="document.getElementById('project-photo').src = window.URL.createObjectURL(this.files[0])"/>

									
								</div>
								
								<input type="text" disabled="disabled" id="agent-file-name" class="main-input" />
							</div>
						</div>
						<div class="col-xs-3"></div>
					</div>

					<div class="row">
						<div class="col-xs-2"></div>
						<div class="col-xs-8">
							<div class="center-button-cont margin-top-60">
							<button type="submit" name="submit_ad" class="button-primary button-shadow button-full">Submit Project</button>
								
							</div>
						</div>
						<div class="col-xs-2"></div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>	

	
<?php 
    include_once 'lib/footer.php'; 
    ?>



	</body>
</html>