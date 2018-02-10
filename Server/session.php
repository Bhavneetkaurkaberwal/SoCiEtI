<?php
   include("config.php");

	session_start();// Starting Session
// Storing Session
	$user_id=$_SESSION['user_id'];
	//$role = $_SESSION['Role'];
// SQL Query To Fetch Complete Information Of User
	$ses_sql = "Select user_id from users where user_id='$user_id'";
	$sql = mysqli_query($conn,$ses_sql);
	$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
	
	$login_session =$row['user_id'];
	if(!isset($login_session)){
		mysqli_close($conn); // Closing Connection
	}


?>
