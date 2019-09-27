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

//When the form is submitted:
if (isset($_POST['submit']))
{
	//if a file has been selected:
	$file_name = $_FILES['image']['name'];
	if($file_name != ""){
		// set variables to store file information such as name, and tmp-name
		$errors= array();
		$file_name = $_FILES['image']['name'];
		$file_size =$_FILES['image']['size'];
		$file_tmp =$_FILES['image']['tmp_name'];
		$file_type=$_FILES['image']['type'];
		//get the extension of the file (last cahracters after the .)
		$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
		//set a list of the extensions that are allowed to uploaded
		$extensions= array("jpeg","jpg", "PNG");
		//if the file extension of the file is not in the list we created earlier then add the following to the error array
		if(in_array($file_ext,$extensions)=== false){
			$errors[]="extension not allowed, please choose a JPEG, png or jpg.";
		}
			//if the file size is greater than 2097152 then add it to the errors
		if($file_size > 2097152){
			$errors[]='File size must be excately 2 MB';
		}
			// if the errors array is empty do the following
		if(empty($errors)==true){
			move_uploaded_file($file_tmp,"images/".$file_name);
			echo "Success";
		}else{
			//if the errors is not empty, then print the errors array as it is (no format)
			print_r($errors);
		}
	}
	else{
		//If a file hasn't been selected set the filename to "Nothing"
		$file_name = "Nothing";
	}
	//set two variables to store the user input for title and body
	//Replace all spaces in the title of the page with a underscore
	$title = str_replace(' ', '_',$_POST['title']);
	$body = $_POST['body'];

//Insert the new page into the databases pages table, this includes the page title, page content and the file name
	$stmt = "INSERT INTO pages(Menu_name,Content,image_name) VALUES('$title', '$body','$file_name');";

//if the query was successfull redirect the user to the index page
	if($conn->query($stmt) === TRUE) {

		//echo "Page successfully inserted";
		header("location:index.php");

	} else {
//if the query was unsuccessful display this and the error itself
		echo "Something went wrong. Page wasn't inserted.\r\n".$conn->error;

	}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
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
			<h1>Create a new page!</h1>
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" class="form-control" placeholder="Input a Title" required>
				</div>
				<div class="form-group">
					<label>Body</label>
					<textarea name="body" class="form-control" cols="30" rows="10" required></textarea>
				</div>
				Image:
				<br><br>
				
				<div class="input-group">
					<div class="custom-file">
						<input type="file" class="custom-file-input" name="image">
						<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
					</div>
				</div>
				<br>
				<button name="submit" type="submit"  value="Add" class="btn btn-primary">Create !</button>

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
	</script>
	</html>


	

	
	<button type="submit" class="btn btn-primary">Create !</button>
