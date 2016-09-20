<?php

	session_start();
	$error = "";

	if(array_key_exists("logout", $_GET)){ // if user has logged out

		unset($_SESSION);

		setcookie("id","", time() - 60 * 60);
		$_COOKIE["id"] = "";

	} else if( array_key_exists("id", $_SESSION) AND $_SESSION['id'] OR array_key_exists("id", $_COOKIE) AND $_SESSION['id']){

		header("Location: loggedinpage.php"); // keep user logged in

	}

	if(array_key_exists("sign", $_POST)){

		include("connection.php"); // include mySQL connection file


		if(!$_POST['email']){ // form validation

			$error.='<div class="alert alert-danger">'."An email address is required"."</div>";

		}

		if(!$_POST['password']){ // form validation

			$error.='<div class="alert alert-danger">'."A password is required"."</div>";
			
		}

		if($error != ""){ // form validation
			$error = '<div class="alert alert-danger">'."There were error(s) in your form"."</div>".$error;
		} else{

			if($_POST['signUp'] == '1') { //  if user is on sign up form

				$query = "SELECT id FROM `users` WHERE email ='".mysqli_real_escape_string($link ,$_POST['email'])."' LIMIT 1";

					$result = mysqli_query($link,$query);

					if(mysqli_num_rows($result) > 0){ // check if email exists
						$error = '<div class="alert alert-danger">'."This email is already registered"."</div>";
					} else {

						$query = "INSERT INTO `users`(`email`,`password`) VALUES ('".mysqli_real_escape_string($link ,$_POST['email'])."','".mysqli_real_escape_string($link ,$_POST['password'])."')"; // add user to database
						
						if(!mysqli_query($link,$query)){
							$error= "could not sign you up - please try again";
						} else { 

							$query ="UPDATE `users` set password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."'WHERE id=".mysqli_insert_id($link)." LIMIT 1"; // make password secure

							mysqli_query($link,$query);

							$_SESSION['id'] = mysqli_insert_id($link);

							if($_POST['check'] == 1){ // if logged in checkbox is checked

								setcookie("id",mysqli_insert_id($link),time() + 60 * 60 * 24);
							}		

							header("Location: loggedinpage.php"); // keep user logged in
						}
					}


			} else{ // user is on login form

				$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."'";
				// check email

				$result = mysqli_query($link,$query);

				$row = mysqli_fetch_array($result);

				if(isset($row)){ 

					$hashedPassword = md5(md5($row['id']).$_POST['password']);

					if($hashedPassword == $row['password']){ // check entered password against database password

						$_SESSION['id'] = $row['id'];

						if($_POST['check'] == 1){

								setcookie("id",$row['id'],time() + 60 * 60 * 24);
							}		

							header("Location: loggedinpage.php");


					} else{

						$error = '<div class="alert alert-danger">'."Incorrect email or password"."</div>";
					}


				} else {
					$error = "That email or password does not work";
				}

			}

		}

	} 

	
	 
?>



	<?php include("header.php");?> <!-- include html head and CSS file-->

	
	<div class="container text-center" id="homePageContainer">

		<h1>Secret Diary</h1>

		<div id="error"><?php echo $error;?></div>
		<h4>Store your thoughts permenantly and securely</h4>

		<form method="post" id="signUpForm"> <!-- start of sign up form -->

			<div class="form-group">  
		    	<input type="email" class="form-control" id="signEmail" name="email" aria-describedby="emailHelp" placeholder="Your email">	   
		  	</div>

		 	<div class="form-group">  
		    	<input type="password" class="form-control" id="signPassword" name="password" aria-describedby="emailHelp" placeholder="Password">	   
		  	</div>

		  	<label class="form-check-label">
      			<input type="checkbox" class="custom-control-input" name="check" value="1">
      			Stay logged in?
    		</label> <br />

    			<input type="hidden" name="signUp" value="1"> <!-- validate if user is on sign up form on PHP -->

				<button type="submit" class="btn btn-success" name="sign">Sign Up!</button>

				<p><a class="toggleForm">Log in!</a></p>

  		</form> <!-- end of sign up form -->

  	

  		<form method="post" id="logInForm"> <!-- start of login form -->
			<div class="form-group">  
		    	<input type="email" class="form-control" id="signEmail" name="email" aria-describedby="emailHelp" placeholder="Your email">	   
		  	</div>

		 	<div class="form-group">  
		    	<input type="password" class="form-control" id="signPassword" name="password" aria-describedby="emailHelp" placeholder="Password">	   
		  	</div>

		  	<label class="form-check-label">
      			<input type="checkbox" class="custom-control-input" name="check" value="1">
      			Stay logged in?
    		</label> <br />

    			<input type="hidden" name="signUp" value="0">

				<button type="submit" class="btn btn-success" name="sign">Login</button>

				<p><a class="toggleForm">Sign up</a></p>
  		</form> <!-- end of login form -->




	</div>

	<?php include("footer.php")?> // include Javascript and jQuery file