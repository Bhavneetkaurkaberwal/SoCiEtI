<?php
 include("config.php");
 
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
      $password = mysqli_real_escape_string($conn,$_POST['password']); 
      $role = mysqli_real_escape_string($conn,$_POST['role']); 
       
	}

	if (!$conn)
	  {
		  die('Could not connect: ' . mysql_error());
	  }
	$sql2 = "SELECT user_id FROM users Where users.user_id = '$user_id'";
        $query = mysqli_query($conn,$sql2);
        while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
        $newuserid = $row1['user_id'];
	
		if ($newuserid == $user_id)
		{
			echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   window.alert('User already exists. Please try again')
        		   window.location.href='http://localhost/project/prod/new_user.html'
        		   </SCRIPT>");
		}
	}
	$password = base64_encode($password);
	$sql = "INSERT INTO users (user_id, password, Role)
	VALUES ('$user_id','$password','$role')";
	if (!mysqli_query($conn,$sql))
  	{
		die('Error: ');
  	}
	

	 include("/opt/lampp/htdocs/project/prod/session.php");
	
	$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
      	$query = mysqli_query($conn,$sql5);
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$role = $row['role'];
	$action = "User added";
	date_default_timezone_set('Asia/Kolkata');
      	$my_date = date("Y-m-d H:i:s");

	$sql4 = "INSERT INTO system_logs (user_id, Role, date_time, action)
      	VALUES ('$user_id','$role' ,'$my_date','$action')";
      	mysqli_query($conn,$sql4);
	
	echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Added Succesfully')
        window.location.href='http://localhost/project/prod/new_user.html'
        </SCRIPT>");

	

mysqli_close($conn)
?>
