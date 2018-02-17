<?php
   include("config.php");
   session_start();
  
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
     
      $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
      $password = mysqli_real_escape_string($conn,$_POST['password']); 
      
     
	  // Vaildation & Authentication

      $password = base64_encode($password);
    
      $sql = "SELECT user_id FROM users WHERE user_id = '$user_id' and password = '$password'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);
	
      $sql2 = "SELECT role FROM users Where users.user_id = '$user_id'";
      $query = mysqli_query($conn,$sql2);
      while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
      $role = $row1['role'];
     }
      
	  if($count == 0)
	  {
		$action = "login unsuccessful";
		date_default_timezone_set('Asia/Kolkata');
      		$my_date = date("Y-m-d H:i:s");

		$sql4 = "INSERT INTO system_logs (user_id, Role, date_time, action)
      		VALUES ('$user_id','$role' ,'$my_date','$action')";
      		mysqli_query($conn,$sql4);
		  
		echo ("<SCRIPT LANGUAGE='JavaScript'>
           	     window.alert('Username or Password Invalid. Please try again')
                     window.location.href='http://localhost/project/production/login.html'
        	     </SCRIPT>");

	  }
		  
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1 && $role == 'Instructor') {
        

	$action = "logged in";
	date_default_timezone_set('Asia/Kolkata');
      	$my_date = date("Y-m-d H:i:s");

	$sql2 = "INSERT INTO system_logs (user_id, Role, date_time, action)
      	VALUES ('$user_id','$role' ,'$my_date','$action')";
      	mysqli_query($conn,$sql2);

	$_SESSION["user_id"] = $user_id;
	//$_SESSION["Role"] = $role;

	echo ("<SCRIPT LANGUAGE='JavaScript'>
             window.location.href='http://localhost/project/production/dashboard1.php'
             </SCRIPT>"); 
          
        header("Location:dashboard1.php?user_id=".$user_id);

      }   
 
      if($count == 1 && $role == 'Admin') {
	$action = "logged in";
	date_default_timezone_set('Asia/Kolkata');
        $my_date = date("Y-m-d H:i:s");
	$sql3 = "INSERT INTO system_logs (user_id, Role, date_time,action)
        VALUES ('$user_id','$role' ,'$my_date','$action')";
        mysqli_query($conn,$sql3);

	$_SESSION["user_id"] = $user_id;
	//$_SESSION["Role"] = $role;
          
        echo ("<SCRIPT LANGUAGE='JavaScript'>
             window.location.href='http://localhost/project/production/dashboard2.html'
             </SCRIPT>"); 
     }    
      
 } 
   
?>
