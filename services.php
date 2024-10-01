<?php
session_start();
error_reporting(0);

include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Online Banquet Booking System|| Services</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!--// bootstrap-css -->
<!-- css -->
<link rel="stylesheet" href="css/style1.css" type="text/css" media="all" />
<!--// css -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<!-- font -->
<link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300' rel='stylesheet' type='text/css'>
<!-- //font -->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<!-- <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script> -->

<!-- Then include bootstrap js -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> -->


<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script> 
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->
</head>
<body>
	<!-- banner -->
	<div class="banner jarallax">
		<div class="agileinfo-dot">
		<?php include_once('includes/header.php');?>
			<div class="wthree-heading">
				<h2>Services</h2>
			</div>
		</div>
	</div>
	<!-- //banner -->
	<!-- about -->
	<!-- about-top -->
	<div class="about-top">
		<div class="container">
			<div class="wthree-services-bottom-grids">
				<div>
					<h3>Use AI powered search if its will sell soon</h3>
					<div class="form-group">
					<label for="search_day">Select a date:</label>
					<input type="date" class="form-control" style="font-size: 20px" required="true" name="search_day" id="search_day">
				</div>
				<button class="btn btn-primary" onclick="searchPrediction()">Search</button>

				<h3 class="mt-4">Prediction Result:</h3>
				<div id="result" class="alert alert-info" style="display: none;"></div>


				</div>
				
				<p class="wow fadeInUp animated" data-wow-delay=".5s">List of services which is provided by us.</p>
					<div class="bs-docs-example wow fadeInUp animated" data-wow-delay=".5s">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Package Name</th>
									<th>Description</th>
									<!-- <th>Price</th> -->
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							
								<?php
$sql="SELECT * from tblservice";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
								
								<tr>
									<td><?php echo htmlentities($cnt);?></td>
									<td><?php  echo htmlentities($row->ServiceName);?></td>
									<td><?php  echo htmlentities($row->SerDes);?></td>
									<?php if($_SESSION['obbsuid']==""){?>
									<td><a href="login.php" class="btn btn-primary">Book Services</a></td>
									<?php } else {?>
									<td><a href="book-services.php?bookid=<?php echo $row->ID;?>" class="btn btn-primary">Book Services</a></td><?php }?>
								</tr> <?php $cnt=$cnt+1;}} ?>
							</tbody>
						</table>
					</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //about-top -->
	
	<!-- //about -->

	<!-- jarallax -->
	<script src="js/jarallax.js"></script>
	<script src="js/SmoothScroll.min.js"></script>
	<script type="text/javascript">
		/* init Jarallax */
		$('.jarallax').jarallax({
			speed: 0.5,
			imgWidth: 1366,
			imgHeight: 768
		})
	</script>
	<!-- //jarallax -->
	<script src="js/SmoothScroll.min.js"></script>
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
<script src="js/modernizr.custom.js"></script>

<!-- call ai api to predict -->
<script>
    function searchPrediction() {
        const searchDay = document.getElementById('search_day').value;
        if (!searchDay) {
            alert('Please select a date.');
            return;
        }

        // Construct the URL with the query parameter
        const url = `http://localhost:5000/predict?date=${encodeURIComponent(searchDay)}`;

        // Make a GET request
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('result').innerHTML = `Error: ${data.error}`;
            } else {
                document.getElementById('result').innerHTML = `Predicted bookings for ${data.date}: ${data.response}`;
            }
            document.getElementById('result').style.display = 'block';
        })
        .catch(error => {
            document.getElementById('result').innerHTML = `Error: ${error.message}`;
            document.getElementById('result').style.display = 'block';
        });
    }
</script>

</body>	
</html>