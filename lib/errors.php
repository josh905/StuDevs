<?php  



include_once 'config.php';

if(strpos($url, 'student_sign_up_error=')!==false){
	?>
	<script>
	showstumod();
	</script>
	<?php
}

if(strpos($url, 'business_sign_up_error=')!==false){
	?>
	<script>
	showbusmod();
	</script>
	<?php
}

if(strpos($url, 'log_in_error=')!==false){
	?>
	<script>
	showlogmod();
	</script>
	<?php
}



