<?php 
//Establish connections with database, error logger and global session variables

session_start();
require("db.php");
require('errorLog.php');
//If a user isn't logged in, redirect the current user to the login page
if ($_SESSION['username'] == null ) 
{
	header('Location: Login.php');
}
//if the user isn't an admin, redirect them to the main page (index.php)
if ($_SESSION['AType'] != "Admin") 
{
	header('Location: index.php');
}

if (isset($_POST['SubUser'])) 
{
	//Get the ID of the page that was selected and when the "Edit" or "Change" button is clicked, update that record in the database
	$clicked_id = $_COOKIE["clicked"];
	if ($_POST['SubUser'] == "Admin") 
	{
		$stmt = "UPDATE users SET Account_Type = 'Normal' WHERE ID = '$clicked_id';";
	}
	else
	{
		$stmt = "UPDATE users SET Account_Type = 'Admin' WHERE ID = '$clicked_id';";
	}
	//if the query was successfull redirect the user to the index page
	if($conn->query($stmt) === TRUE) {
		header("location:index.php");

	} else {
//if the query was unsuccessful display this and the error itself
		echo "Something went wrong. User wasn't updated.\r\n".$conn->error;

	}
}
//IF the delete user button is clicked, delete the user with the same ID as the ID of that button.
if (isset($_POST['DelUser'])) 
{
	$userId = $_POST["DelUser"];
	$stmt = "DELETE FROM users WHERE ID = '$userId';";
	//if the query was successfull redirect the user to the index page
	if($conn->query($stmt) === TRUE) {
		header("location:index.php");

	} else {
		//if the query was unsuccessful display this and the error itself
		echo "Something went wrong. User wasn't updated.\r\n".$conn->error;

	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit User</title>
	<!-- Importing all required libraries and frameworks such as JQuery and Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- Setting up the size of the web page to allow for responsive design -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<!-- Linking external stylesheets -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
		<!-- <div class="container"> -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">

				<!-- LOGOUT BUTTON -->
				<form method="POST">
					<button name = "logout" type = "submit" class="btn btn-outline-success"><i class="fas fa-sign-out-alt"></i><span>  Logout</span></button>
				</form>	

				<ul class="navbar-nav ml-auto">

					<!-- Generate the button for statistics seperatly -->
					<li class='nav-item active'><button class='btn btn-outline-primary'><a class='nav-link' href='index.php'>Back</a></button></li> &nbsp;

					<!-- Admin only buttons -->
					<?php 
					if ($_SESSION['AType'] == "Admin") 
					{
							//Generate four buttons for the CRUD operations
						echo "<button class=' Mbtn btn btn-outline-success' id='new'> <i class='fa fa-plus'></i><span>Add New Page</span></button> &nbsp;";
						echo "<button class='btn btn-outline-success' id='edit'><i class='fa fa-pen'></i> <span>Edit</span></button> &nbsp;";
						echo "<button class='btn btn-outline-danger' id='delete'><i class='fa fa-trash'></i><span> Delete</span></button> &nbsp;" ;
						echo "<button class='btn btn-outline-success' id='editUser'><i class='fa fa-user-plus'></i><span>  Edit users</span></button>";
					}

					?>

				</ul>
			</div>
			<!-- </div> -->
		</nav>
		<div class="container">
			<?php 
	//loop through all the users and generate buttons for each of them
			require('db.php');
			$stmt = "SELECT * FROM users";
			if($result = $conn->query($stmt))
			{
				echo "<form method='POST'>";
				while($row = $result->fetch_assoc()){
					if ($row['Account_Type'] == "Normal") 
					{
						echo $row['Username'].": <button name = 'SubUser' value=".$row['Account_Type']." class='btn btn-outline-success' type = 'submit' onClick='func(this.id)' id='".$row['ID']."'><i class='fa fa-user-plus'></i> <span>Make Admin</span></button> &nbsp;";
						echo "<button name = 'DelUser' value=".$row['ID']." class='btn btn-outline-danger' type = 'submit'><i class='fa fa-user-slash'></i> <span>Delete User</span></button><br><br>";

					}
					else
					{
						if ($_SESSION['username'] != $row['Username']) {
							echo $row['Username'].": <button name = 'SubUser' value=".$row['Account_Type']." class='btn btn-outline-warning' type = 'submit' onClick='func(this.id)' id='".$row['ID']."'><i class='fa fa-user-minus'></i> <span>Make Normal</span></button> &nbsp;";
							echo "<button name = 'DelUser' value=".$row['ID']." class='btn btn-outline-danger' type = 'submit'><i class='fa fa-user-slash'></i> <span>Delete User</span></button><br><br>";
						}
					}
					
				}
				echo "</form>";
			}
			else 
			{
				echo "Could not fetch articles.";
			}

			?>
		</div>
	</body>
	<script>
		function func(clicked_id)
		{
		//when a checkbox is clicked, store it's ID in this cookie
		document.cookie = "clicked=" + clicked_id;
	}

</script>
</html>