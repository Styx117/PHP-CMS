<?php 
//DATABASE connection and error log file connection
require("db.php");
require('errorLog.php');

?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<meta charset="UTF-8">	
<!-- import all necessary external resources -->
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
			echo "<i class='fa-fw fa-3x fas fa-edit'></i><br>"; 
			//if the rgister button is clicked, check if the user already exists, if they don't create the new user and take the user back to the login page
			if (isset($_POST['register'])) 
			{
				$formUsername = $_POST['Uname'];
				$formPassword = $_POST['Pass'];
				$sql = "SELECT * FROM users WHERE Username = '$formUsername' LIMIT 1";
				$query = mysqli_query($conn,$sql);
	
				if (mysqli_num_rows($query) == 0)
				{

					$stmt = "INSERT INTO users(Username,Password) VALUES('$formUsername', '$formPassword');";

					if($conn->query($stmt) === TRUE) {

						header("location:Login.php");

					} 
					else {

						echo "Something went wrong. User wasn't inserted.\r\n".$conn->error;

					}
				}
				else
				{
					echo "USER ALREADY EXISTS <br> PLEASE SELECT ANOTHER NAME";
				}
			}
			?>
			<br>
			<br>
			<form Method = "POST">
				<!-- Setup the username and password boxes-->
				Username: <br> <input type="text" name="Uname" min="1" max="10"><br>
				<br><br>
				Password: <br><input type="Password" name="Pass" min="1" max="10"><br>
				<br><br>
				<button name = "register" type = "submit" id="reg" class="btn"><i class="fas fa-edit"></i><span>  Register</span></button>

			</form>	
			<br>
			<!-- Display the back butto nto take the mback to the login page -->
			<button id="back" class="btn"><i class="fas fa-arrow-alt-circle-left"></i><span>  Back</span></button>
		</div>
	</div>
</body>
<script>
	//When the back button is clicked, redirect them to the login page
	$("#back").click(function()
	{
		window.location.href = "Login.php";
	})
</script>
</html>



