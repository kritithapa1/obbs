<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<?php
session_start();
?>
    <div class="header">
                    <div class="container">
                        <div class="header-top-grids">
                       
                            <div class="agileits-logo">
                                <h1><a href="index.php">OBBS </a></h1>
                            </div>
                           
                            <div class="top-nav">
                                <nav class="navbar navbar-default">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
    
                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
                                        <nav>
                                            <ul class="nav navbar-nav">
                                                <li><a href="index.php">Home</a></li>
                                                <li><a href="about.php">About</a></li>
                                                <li><a href="services.php">Services</a></li>
                                                <li><a href="mail.php">Mail Us</a></li>
                                                <?php if (strlen($_SESSION['obbsuid']!=0)) { ?>
                                                <li class=""><a href="#" class="dropdown-toggle hvr-bounce-to-bottom" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account<span class="caret"></span></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="hvr-bounce-to-bottom" href="profile.php">Profile</a></li>
                                                        <li><a class="hvr-bounce-to-bottom" href="booking-history.php">Booking History</a></li>   
                                                         <li><a class="hvr-bounce-to-bottom" href="change-password.php">Change Password</a></li>
                                                        <li><a class="hvr-bounce-to-bottom" href="logout.php">Logout</a></li>        
                                                    </ul>
                                                </li>
                                                <li>
                            <a href="#" class="icon-button" data-toggle="dropdown">
                            <span class="label label-pill label-danger count" style="border-radius:10px;"></span><span class="material-icons md-light">notifications</span>
                                
                                                         
                            </a>
                    <ul class="dropdown-menu">
                    
    
                                                
    
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- /.navbar-collapse -->
                                </nav>
                            </div>
                            <!-- <br> -->
                           
                            </div>
                         
                            
                            <?php if (strlen($_SESSION['obbsuid']==0)) {?>
                            <div class="collapse navbar-collapse nav-wil">
                                <ul style="color: white;">
                                    <li ><a href="login.php" style="color: white;">Login</a></li>
                                                <li> <a href="signup.php"  style="color: white;">Register</a></li>
                                                <li><a href="admin/login.php"  style="color: white;">Admin</a></li>
                                   <?php } ?>
                                </ul>
    
                            </div>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                            </div>
          
    <!-- First include jquery js -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    
    <!-- Then include bootstrap js -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/notification.css" rel="stylesheet" type="text/css" media="all" />
    
    
    <script>
    $(document).ready(function(){
     
     function load_unseen_notification(view = '')
     {
      $.ajax({
       url:"includes/fetch.php",
       method:"POST",
       data:{view:view},
       dataType:"json",
       success:function(data)
       {
       
        if(data.unseen_notification > 0)
        {
         $('.count').html(data.unseen_notification);
        }
       }
      });
     }
     
     load_unseen_notification();
     
    
     
     $(document).on('click', '.icon-button', function(){
      $('.count').html('');
      load_unseen_notification('yes');
     });
     
     setInterval(function(){ 
      load_unseen_notification();; 
     }, 5000);
     
    });
    </script>