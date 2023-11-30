<?php 
	session_start();
	require("db_conn.php");
	
	if(!isset($_SESSION["login_info"]))
	{
		header("location:login_fail.php");
	}
	

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reminder System</title>
        <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="css/style.css" rel="stylesheet">
        <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
            rel='stylesheet'>
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <div class="sidebar">
							<h1><center>Welcome to the reminder application </cemter></h1>
							<h1><center>
							<?php
								date_default_timezone_set('Asia/Kolkata');

								// Get the current date and day
								$currentDateWithDay = date('l, F j, Y');

								// Print the result
								echo "Today is $currentDateWithDay";
							?>

							</center></h1>
                            <ul class="widget widget-menu unstyled">
                                <li class="active"><a href="login.php"><i class="menu-icon icon-home"></i>Home
                                </a></li>
                                 <li><a href="set_reminder.php"><i class="menu-icon icon-inbox"></i>Set Reminder</a></li>
                                <li><a href="modify_reminder.php"><i class="menu-icon icon-book"></i>Modify Reminder</a></li>
                                <li><a href="disable_reminder.php"><i class="menu-icon icon-tasks"></i>Disable Reminder </a></li>
                                <li><a href="delete_reminder.php"><i class="menu-icon icon-list"></i>Delete Reminder</a></li>
                                <li><a href="enable_reminder.php"><i class="menu-icon icon-list"></i>Enable Reminder</a></li>
								<li><a href="view_reminder.php"><i class="menu-icon icon-list"></i>View your Reminder</a></li>
                            </ul>
                            <ul class="widget widget-menu unstyled">
                                <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                    	<center>
                           	<div class="card" style="width: 50%;"> 
                    			<img class="card-img-top" src="images/profile2.png" alt="Card image cap">
                    			<div class="card-body">

                                <?php
                                $user = $_SESSION['login_info'];
		
                                $sql="select * from reminder_system.users_data where User_Name='$user[User_Name]'";
                                $result=$con->query($sql);
                                $row=$result->fetch_assoc();

                                $name=$row['User_Name'];
                                $email=$row['EmailId'];
                                $mobno=$row['Phone_num'];
                                ?>    
                    				<i>
                    				<h1 class="card-title"><center><?php echo $name ?></center></h1>
                    				<br>
                    				<p><b>Email ID: </b><?php echo $email ?></p>
                    				<br>
                    				<p><b>Mobile number: </b><?php echo $mobno ?></p>
                    				</b>
                                </i>

                    			</div>
                    		</div>
                            <br>
                            <!-- <a href="edit_student_details.php" class="btn btn-primary">Edit Details</a>     -->
      					</center>              	
                    </div>
                    
                    <!--/.span9-->
                </div>
            </div>
            <!--/.container-->
        </div>
<div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2023 Reminder System </b>
            </div>
        </div>
        
        <!--/.wrapper-->
        <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="scripts/common.js" type="text/javascript"></script>
      
    </body>

</html>