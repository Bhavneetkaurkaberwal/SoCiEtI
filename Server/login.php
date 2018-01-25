<?php
   include("config.php");
   session_start();
  
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
     
      $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
      $password = mysqli_real_escape_string($conn,$_POST['password']); 
      
	  // Vaildation & Authentication
	  
      $sql = "SELECT user_id FROM users WHERE user_id = '$user_id' and password = '$password'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);

	  // Checks the role of user
      $sql2 = "SELECT role FROM users Where users.user_id = '$user_id'";
      $query = mysqli_query($conn,$sql2);
      while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
      $role = $row1['role'];
     }
      
	  // If wrong username or password appropriate msg
	  if($count == 0)
	  {
		  echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.alert('Username or Password Invalid. Please try again')
           window.location.href='http://localhost/project/production/login.html'
        </SCRIPT>");

	  }
		  
      // If result matched $myusername and $mypassword, table row must be 1 row
		
		
	  // If instructor logs in redirects to instructor UI
      if($count == 1 && $role == 'Instructor') {
        /* echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.location.href='http://localhost/project/production/dashboard1.php'
        </SCRIPT>"); */
          
        header("Location:dashboard1.php?user_id=".$user_id);

      }   
 
	  // If Admin logs in redirects to Admin UI
      if($count == 1 && $role == 'Admin') {
         echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.location.href='http://localhost/project/production/dashboard2.html'
           </SCRIPT>"); 
     }    
      
   
      
   
?>
