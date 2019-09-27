<?php 
//Include necessary files/connections such as connecting to the database, the error log file and the global Session variables  
session_start();
require('db.php');
require('errorLog.php');

// If the username and password boxes have been filled out: make variables storing what the user entered
if (isset($_POST['Uname']) || isset($_POST['Pass'])) 
{ 
	$formUsername = strip_tags($_POST['Uname']);
	$formPassword = strip_tags($_POST['Pass']);
}

//If a user is logged in already, redirect them to index.php
if (isset($_SESSION['username'])) 
{ 
	if ($_SESSION['username'] != "") 
	{
		header('Location: index.php');
	}
}

?>

<html>
<head>
	<title>Login</title>
	<!-- import all necessary external resources -->
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Reference the CSS file -->
	<link rel="stylesheet" href="css/style.css">
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body id="b">
	<div class="container LorR">
		<div class = "col-md-4" id="loginCard">
			<br>
			<br>
			<?php
			echo "<i class='fa-fw fa-3x fas fa-user-circle'></i>";
			//if the login button is clicked: if the information is correct, log them in. 
			if (isset($_POST['login'])) 
			{
				//set a query to check if the user exists in the database, and their password matches
				$sql = "SELECT * FROM users WHERE Username = '$formUsername' AND Password = '$formPassword'";
				
				$query = mysqli_query($conn,$sql);
				if (!$query) {
					printf("Error: %s\n", mysqli_error($conn));
					exit();
				}

				//Get the information of that user if it exists
				$row = mysqli_fetch_array($query);
				$id = $row['ID'];
				$AType = $row['Account_Type'];
				$db_password = $row['Password'];
				$db_username = $row['Username'];

				// if the informaiton the user entered matches the information from the database query, make global variables of that information
				if ($formPassword == $db_password && $formUsername == $db_username) 
				{
					$_SESSION['AType'] = $AType;
					$_SESSION['username'] = $formUsername;
					$_SESSION['ID'] = $id;
					header('Location: index.php');
				}
				else
				{
					echo ("<br> INCORRECT CREDENTIALS!");
				}

			}
			?>

			<br>
			<br>
			<!-- Setup the username and password boxes -->
			<form Action = "Login.php" Method = "POST">
				
				Username: <br> <input type="text" name="Uname"><br>
				<br>
				<br>
				Password: <br><input type="Password" name="Pass"><br>
				<br>
				<br>
				<button name = "login" type = "submit" value="Login" class="btn"><i class="fas fa-sign-out-alt"></i><span>  Login</span></button>		
				
				
			</form>
			<!-- make button for new users to register -->
			<button name = "register" id="reg" class="btn"><i class="fas fa-edit"></i><span>  Register</span></button>
		</div>


	</div>
</body>
<script>
	//when the register button is clickedm redirect the user to the registartion page.
	$("#reg").click(function()
	{
		window.location.href = "Register.php";
	})

</script>

</html>