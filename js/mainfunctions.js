//main js functions for all pages


function showbusmod(){
	$('#business-register-modal').modal('show');
	$('#student-register-modal').modal('hide');
	$('#login-modal').modal('hide');
	$('#forgot-modal').modal('hide');
}

function showstumod(){
	$('#student-register-modal').modal('show');
	$('#business-register-modal').modal('hide');
	$('#login-modal').modal('hide');
	$('#forgot-modal').modal('hide');
}




function showlogmod(){
	$('#login-modal').modal('show');
	$('#student-register-modal').modal('hide');
	$('#business-register-modal').modal('hide');
	$('#forgot-modal').modal('hide');
}


function showfgmod(){
	$('#forgot-modal').modal('show');
	$('#student-register-modal').modal('hide');
	$('#login-modal').modal('hide');
	$('#business-register-modal').modal('hide');
}




function focusSignupFirstName(){
    $('#signfirst').focus();
}

function focusSignupLastName(){
    $('#signlast').focus();
}

function focusSignupEmail(){
    $('#signemail').focus();
}


function focusSignupPassword(){
    $('#signpword').focus();
}


function focusLoginEmail(){
    $('#logemail').focus();
}

function focusLoginPassword(){
    $('#logpword').focus();
}


//for requirements calculation
var recPrice = 180; 

function calcPrice(req, value) {
  if (req.checked){
  	if(recPrice<180) recPrice=180+value;
  	else recPrice+=value; 
  }
  else{
  	recPrice-=value; 
  	if(recPrice<180) recPrice=180;
  }
  var tot = "€"+recPrice;
  $('#recPrice').text(tot);
}

function priceCompare(){
	var myPrice = document.getElementById("myPrice").value;
	if(myPrice<recPrice){
		if(myPrice<(recPrice*0.75)){
			if(!confirm("We consider your price to be too low.\nStudents are often unwilling to work for less than the recommended price.\nAre you sure you want to go ahead with this price?")){
				return false;
			} 
		}
	}
	if(myPrice>recPrice){
		if(myPrice>(recPrice*1.25)){
			if(!confirm("We consider your price to be too high.\nAre you sure you want to go ahead with this price?")){
				return false;
			}
			
		}
	}
	return true;
}

