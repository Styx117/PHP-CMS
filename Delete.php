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
//if the user isn#t an admin, redirect them to the main page (index.php)
if ($_SESSION['AType'] != "Admin") 
{
	header('Location: index.php');
}
//Storing the ID of the currently selected page in the cookies and displaying it to the user:
//The following block of code will fetch the page that is "currently" selected and delete it

if (isset($_POST['submit']) && $_COOKIE["current"] != "None") 
{
	$sct = $_COOKIE["selected"];
	$stmt = "DELETE FROM pages WHERE ID = '$sct';";


	if($conn->query($stmt) === TRUE) {

		echo "Page successfully deleted";
		setcookie("current", "None");
		header("location:index.php");


	} 
	else {
		//if the query was unsuccessful display this and the error itself
		echo "Something went wrong. Page wasn't deleted.\r\n".$conn->error;

	}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Delete</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
			if ($_COOKIE["current"] != null || $_COOKIE["current"] != ""){
				echo "<h1>Delete a page</h1>";
				echo "<legend>Currently selected page: ". $_COOKIE["current"]."</legend><br>";
				echo "<h3>Select page</h3>";
			}
	//Loop through all paegs in the database and generate a checkbox for each of them
			$stmt = "SELECT * FROM pages";
			if($result = $conn->query($stmt))
			{
				while($row = $result->fetch_assoc()){
					echo "<input type='checkbox' class='radio' name='".$row['Menu_name']."'value=".$row['ID']." onclick='GetID(this);'>". $row['Menu_name']."<br>";
				}
			}
			else 
			{
		//if the query was unsuccessful display this 
				echo "Could not fetch articles.";
			}

			?>
			<form method="POST">
				<button name="submit" type="submit"  value="Delete" class="btn btn-danger">Delete !</button>
			</form>
		</div>
	</body>


	<script>
		$("#new").click(function()
		{
			window.location.href = "Add.php";
		})

		$("#edit").click(function()
		{
			window.location.href = "Edit.php";
		})

		$("#delete").click(function()
		{
			window.location.href = "Delete.php";
		})

		$("#editUser").click(function()
		{
			window.location.href = "EditUser.php";
		})
//make sure only one checkbox can be selected at a time
$('input[type="checkbox"]').on('change', function() {
	$('input[type="checkbox"]').not(this).prop('checked', false);

});

function GetID(Check)
{
		//when a checkbox is selected refresh the page.
		document.cookie = "selected=" +Check.value;
		document.cookie = "current=" +Check.name;
		location.reload();
	}
</script>

</html>








