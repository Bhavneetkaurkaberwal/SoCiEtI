<?php
include("config.php");
include("session.php");
//session_start();
// Storing Session
	$user_id=$_SESSION['user_id'];
	//$role = $_SESSION['Role'];
// SQL Query To Fetch Complete Information Of User
	$ses_sql = "Select user_id from users where user_id='$user_id'";
	$sql = mysqli_query($conn,$ses_sql);
	$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
	
	$login_session =$row['user_id'];
      $sql2 = "SELECT role FROM users Where users.user_id = '$user_id'";
      $query = mysqli_query($conn,$sql2);
      while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
      $role = $row1['role'];
     }
	if(!isset($login_session)){
		mysqli_close($conn); // Closing Connection
	}
	session_destroy();
	$action = "logged out";
	date_default_timezone_set('Asia/Kolkata');
        $my_date = date("Y-m-d H:i:s");
	$sql3 = "INSERT INTO system_logs (user_id, Role, date_time,action)
        VALUES ('$user_id','$role' ,'$my_date','$action')";
        mysqli_query($conn,$sql3);


header("location: login.html");
?> 

