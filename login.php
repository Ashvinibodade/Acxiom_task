<?php 
	session_start();
	require("db_conn.php");

?>

<!DOCTYPE html>
<html lang="en">
	<?php include "header.php";?>

	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <!-- <a class="navbar-brand" href="index.php">Simple Birthday Reminder</a> -->
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		</nav>

		<div class='container mt-3'>
			<div class="jumbotron"><h2 class='text-muted text-center'>Reminder System</h2></div>
			<div class='row'>
				<div class='col-md-5 mx-auto'>
					<h3 class='text-muted text-center'>LOGIN</h3>

					<?php 
						if(isset($_POST["login"]))
						{
							$u=$_POST['uname'];
 							$p=$_POST['upass'];

							$sql="select * from reminder_system.users_data where User_name='$u' and Password='$p'";
				
							$res=$con->query($sql);
							if($res->num_rows>0)
							{
								$row=$res->fetch_assoc();
								$_SESSION["login_info"]=$row;
								header('location:home.php');
							}else{
								header('location:login_fail.php');
							}
						}
					?>
					<form action='login.php' method='post'>
						<div class="form-group">
							<label>User Name</label>
							<input type="text" class="form-control" name='uname'  placeholder="UserName" required>
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name='upass' placeholder="Password" required>
						</div>
						<div class="form-group">
							<input type='submit' name='login' value='Login' class='btn btn-primary'>
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</body>
</html>