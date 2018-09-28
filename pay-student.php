<?php

include_once 'lib/config.php';

if(!isset($_SESSION['busId'])){
	header("Location: index.php?log_in_as_a_business");
	exit();
}

//if price from progress page is empty or not set
if(empty($_SESSION['projPrice']) || !(isset($_SESSION['projPrice']))){
	header("Location: index.php?go_to_project_progress_page_if_you_have_a_project");
	exit();
}

$title = $_SESSION['projTitle'];
$price = $_SESSION['projPrice'];
$projId = $_SESSION['projId'];
$busRef = $_SESSION['accountRef'];
$stuRef = $_SESSION['stuRef'];
$fullName = $_SESSION['fullName'];
$busEmail = $_SESSION['fullName'];
$priceInCent = $price * 100;
$studevsCut = $price * 0.05;
$studentReceive = $price * 0.95;


require_once('stripe/init.php');

$stripe = array(
  "secret_key"      => "sk_test_u9Ftbq6FaqcvYUvCvZZn491y",
  "publishable_key" => "pk_test_uG6UhJbgp4eUemkoqhfopLgf"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);





if(isset($_POST['stripeToken'])){
	$token  = $_POST['stripeToken'];

	$customer = \Stripe\Customer::create(array(
	      'email' => $busEmail,
	      'source'  => $token
	  ));

	  $charge = \Stripe\Charge::create(array(
	      'customer' => $customer->id,
	      'amount'   => $priceInCent,
	      'currency' => 'eur'
	  ));




	$statement = $conn->prepare("INSERT INTO payment (bus_ref, stu_ref, total_amount, stu_amount, studevs_cut) VALUES (?, ?, ?, ?, ?)");

	$statement->bind_param("sssss", $pr1, $pr2, $pr3, $pr4, $pr5);

	$pr1 = $busRef;
	$pr2 = $stuRef;
	$pr3 = $price;
	$pr4 = $studentReceive;
	$pr5 = $studevsCut;

	$statement->execute();

	$respay = $conn->query($statement);

	$s1 = $conn->prepare("UPDATE student SET bus_proj=NULL WHERE acc_ref=?");
	$s1->bind_param("s", $p1); //this must be "s" !
	$p1 = $stuRef;
	$s1->execute();
	$r1 = $conn->query($s1);

	$s2 = $conn->prepare("UPDATE business SET stu_proj=NULL WHERE acc_ref=?");
	$s2->bind_param("s", $p2); //this must be "s" !
	$p2 = $busRef;
	$s2->execute();
	$r2 = $conn->query($s2);

	$_SESSION['busRef'] = NULL;
	$_SESSION['stuRef'] = NULL;


	$s3 = $conn->prepare("DELETE FROM project WHERE project_id=?");
	$s3->bind_param("s", $p3); //this must be "s" !
	$p3 = $projId;
	$s3->execute();
	$r3 = $conn->query($s3);

  

  header("Location: profile.php?payment_transaction_successful");
  exit();

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>StuDevs | Project Progress</title>
	<?php include_once 'lib/metalinks.php'; ?>

	<!-- highcharts gantt chart JS link -->
	<script src="https://github.highcharts.com/gantt/highcharts-gantt.src.js"></script>

</head>
<body>
<div class="loader-bg"></div>
<div id="wrapper">

<!-- Page header -->	
	<header>
		<?php include_once 'lib/navbar.php'; ?>
    </header>
<section class="section-light section-top-shadow">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-9 col-md-push-3">
				<div class="row margin-top-30">
					<div class="col-xs-12 col-sm-9 col-md-8">


					<div class="labelled-input">
					<label for="fullName">Payment From: </label><input id="fullName" readonly name="fullName" type="text" class="input-full main-input" placeholder="" value="<?php echo $fullName; ?>"/>
					<div class="clearfix"></div>

					<div class="labelled-input">
					<label for="busRef">Your Ref Num: </label><input id="busRef" readonly name="busRef" type="text" class="input-full main-input" placeholder="" value="<?php echo $busRef; ?>"/>
					<div class="clearfix"></div>

					<div class="labelled-input">
						<label for="stuRef">Student Ref Num: </label><input id="stuRef" readonly name="stuRef" type="text" class="input-full main-input" placeholder="" value="<?php echo $stuRef; ?>"/>
						<div class="clearfix"></div>
					</div>

					</div>
					<div class="labelled-input">
						<label for="projTitle">Project: </label><input id="projTitle" readonly name="projTitle" type="text" class="input-full main-input" placeholder="" value="<?php echo $title; ?>"/>
						<div class="clearfix"></div>
					</div>
					<div class="labelled-input">
						<label for="projPrice">Total Payment: </label><input id="projTitle" readonly name="projTitle" type="text" class="input-full main-input" placeholder="" value="<?php echo "€" . $price; ?>"/>
						<div class="clearfix"></div>
					</div>

				<div class="row margin-top-30">
					<div class="col-xs-4"></div>
					<div class="col-xs-4">
					<form action="pay-student.php" method="post">
					  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			  		        data-key="<?php echo $stripe['publishable_key']; ?>"
			  		        data-currency="eur" 
			   		       data-description="<?php echo 'Payment for ' . $title; ?>"
			   		       data-amount="<?php echo $priceInCent; ?>"
			  		        data-locale="auto">        
					  </script>
					</form>
					</div>
					<div class="col-xs-4"></div>
				</div>

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