<?php 
//Establish connections with the global session variables
session_start();
//If a user isn't logged in, redirect the current user to the login page
if ($_SESSION['username'] == null) 
{
	header('Location: Login.php');
}
//if the logout button is clicked, destroy the session and redirect the user to the login page 
if (isset($_POST['logout']))
{
	session_destroy();
	header('Location: login.php');	
}

$AmountOfAdmins = 0; 
$AmountOfNormals = 0; 
?>
<!DOCTYPE html>
<html>
<head>
	<title>In The Zone</title>
	<meta charset="UTF-8">
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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		$( function() {
			$( "#tabs" ).tabs({
				show: { effect: "blind", duration:800 }
			});
		} );
	</script>

</head>
<body>
	<div id="tabs">
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
						
						<?php 
						//Loop through all the records in the pages table and generate a button for each of them in the navigation bar
						require('db.php');

						$stmt = "SELECT * FROM pages";

						if($result = $conn->query($stmt))
						{
							while($row = $result->fetch_assoc())
							{
								$title = str_replace('_', ' ',$row['Menu_name']);
								echo "<li class='nav-item active'><button class='btn btn-outline-primary'><a class='nav-link' href='#".$row['Menu_name']."'>".$title."</a></button></li> &nbsp;";
							}


						} 
						else {

							echo "Could not fetch articles.";

						}
						?>
						
						<!-- Generate the button for statistics seperatly -->
						<li class='nav-item active'><button class='btn btn-outline-primary'><a class='nav-link' href='#stats'>Statistics</a></button></li> &nbsp;

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

			<?php 
			require('db.php');
			//Loop through all the records in the pages table and generate DIVS for each of them with a background image and a nice layout using bootstrap containers
			$stmt = "SELECT * FROM pages";

			if($result = $conn->query($stmt))
			{
				while($row = $result->fetch_assoc()){
					if ($row['image_name'] == "Nothing") {
						$image = "space.jpg";
					}
					else{
						$image = $row['image_name'];
					}
					$title = str_replace('_', ' ',$row['Menu_name']);
					echo "<div id='".$row['Menu_name']."'><div class='bgimgs' style='background-image:url(images/".$image.");'><div class='layerMain'>".$title."</div></div><div class='container'><br><br>".$row['Content']."</div></div>";

				}

			}

			//loop through the users table and count the amout of admins and normals
			$stmt = "SELECT * FROM users";

			if($result = $conn->query($stmt))
			{
				while($row = $result->fetch_assoc()){
					if ($row['Account_Type'] == "Admin") 
					{
						$AmountOfAdmins++; 
					}
					else
					{
						$AmountOfNormals++; 
					}

				}

			}



			else 
			{

				echo "Could not fetch articles.";

			}


			?>

			<div class="container">
				<div id='stats'><h1>Pie chart</h1> <br><br><br>The pie chart below is an example for how pie charts would look on this website, it is currently showing the amount of Admins and Normal users on this website.<br><div id="piechart"></div></div>
			</div>
		</div>
	</body>
	<script>
		//make redirection functions ofr all the buttons on the nav bar
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
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
	var val = "<?php echo $AmountOfAdmins; ?>";
	var val1 = "<?php echo $AmountOfNormals; ?>";
	
	var data = google.visualization.arrayToDataTable([
		['UserType', 'Amount of Users'],
		['Admin', parseInt(val)],
		['Normal', parseInt(val1)]
		]);

  // Optional; add a title and set the width and height of the chart
  var options = {
  	'title':'Types of users', 
  	'width':600, 
  	'height':600,
  	backgroundColor: 'transparent'
  };

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}

function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}
</script>
</html>