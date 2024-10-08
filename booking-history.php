<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['obbsuid'] == 0)) {
	header('location:logout.php');
} else {


	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>Online Banquet Booking System|| Booking History </title>

		<script
			type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
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
		<link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i"
			rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300'
			rel='stylesheet' type='text/css'>
		<!-- //font -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<!-- <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script> -->

		<!-- Then include bootstrap js -->
		<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> -->


		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(".scroll").click(function (event) {
					event.preventDefault();
					$('html,body').animate({ scrollTop: $(this.hash).offset().top }, 1000);
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
				<?php include_once('includes/header.php'); ?>
				<div class="wthree-heading">
					<h2>Booking History</h2>
				</div>
			</div>
		</div>
		<!-- //banner -->
		<!-- about -->
		<!-- about-top -->
		<div class="about-top">
			<div class="container">
				<div class="wthree-services-bottom-grids">

					<p class="wow fadeInUp animated" data-wow-delay=".5s">List of booking.</p>
					<div class="bs-docs-example wow fadeInUp animated" data-wow-delay=".5s">
						<table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
							<thead>
								<tr>
									<th class="text-center"></th>
									<th>Booking ID</th>
									<th class="d-none d-sm-table-cell">Cutomer Name</th>
									<th class="d-none d-sm-table-cell">Mobile Number</th>
									<th class="d-none d-sm-table-cell">Email</th>
									<th class="d-none d-sm-table-cell">Message</th>
									<th class="d-none d-sm-table-cell">Booking Date</th>
									<th class="d-none d-sm-table-cell">Status</th>
									<th class="d-none d-sm-table-cell" style="width: 15%;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$uid = $_SESSION['obbsuid'];
								$sql = "SELECT tbluser.FullName,tbluser.MobileNumber,tbluser.Email,Message,tblbooking.BookingID,tblbooking.BookingDate,tblbooking.Status,tblbooking.ID,tblbooking.UpdationDate from tblbooking join tbluser on tbluser.ID=tblbooking.UserID where tblbooking.UserID='$uid'";
								$query = $dbh->prepare($sql);
								$query->execute();
								$results = $query->fetchAll(PDO::FETCH_OBJ);

								$cnt = 1;
								if ($query->rowCount() > 0) {
									foreach ($results as $row) { ?>
										<tr>
											<td class="text-center"><?php echo htmlentities($cnt); ?></td>
											<td class="font-w600"><?php echo htmlentities($row->BookingID); ?></td>
											<td class="font-w600"><?php echo htmlentities($row->FullName); ?></td>
											<td class="font-w600"><?php echo htmlentities($row->MobileNumber); ?></td>
											<td class="font-w600"><?php echo htmlentities($row->Email); ?></td>
											<td class="font-w600"><?php echo htmlentities($row->Message); ?></td>
											<td class="font-w600">
												<span
													class="badge badge-primary"><?php echo htmlentities($row->BookingDate); ?></span>
											</td>
											<?php if ($row->Status == "") { ?>

												<td class="font-w600"><?php echo "Not Updated Yet"; ?></td>
											<?php } else { ?>
												<td class="d-none d-sm-table-cell">
													<span class="badge badge-primary"><?php echo htmlentities($row->Status); ?></span>
												</td>
											<?php } ?>
											<td class="d-none d-sm-table-cell"><a
													href="view-booking-detail.php?editid=<?php echo htmlentities($row->ID); ?>&&bookingid=<?php echo htmlentities($row->BookingID); ?>"><i
														class="fa fa-eye" aria-hidden="true"></i></a>

												<?php
												// Get current date and approved date in Y-m-d format
												$currentDate = new DateTime();
												$currentDateFormatted = $currentDate->format('Y-m-d');

												$approvedDate = new DateTime($row->UpdationDate);
												$approvedDateFormatted = $approvedDate->format('Y-m-d');

												// Calculate the difference in days
												$approvedDateOnly = new DateTime($approvedDateFormatted);
												$currentDateOnly = new DateTime($currentDateFormatted);

												$interval = $currentDateOnly->diff($approvedDateOnly);
												$daysDiff = $interval->days;

												if ($row->Status == "Approved") {
													if ($daysDiff <= 1) {
														?>
														<a href="initiate-payment.php?bookingid=<?php echo htmlentities($row->BookingID); ?>&uid=<?php echo htmlentities($uid); ?>&bid=<?php echo htmlentities($row->ID); ?>"
															target="_blank" style="padding-left: 1rem;">Pay Now
															<?php echo htmlentities($daysDiff + 1); ?> days left
														</a>
														<?php
													} else {
														?>
														<span style="padding-left: 1rem;">Expired
															<?php echo htmlentities($daysDiff - 1); ?> days ago


														</span>
														<?php
													}
												}
												?>



											</td>
										</tr>
										<?php $cnt = $cnt + 1;
									}
								} ?>



							</tbody>
						</table>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
		<!-- //about-top -->

		<!-- //about -->
		<!-- footer -->

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
			$(document).ready(function () {
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

	</body>

	</html><?php } ?>