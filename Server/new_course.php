<?php
   include("config.php");
   include("/opt/lampp/htdocs/project/production/session.php");
  
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
     
      $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
      $course_id = mysqli_real_escape_string($conn,$_POST['course_id']); 
     }

	if (!$conn)
	  {
		  die('Could not connect: ' . mysql_error());
	  }


      $sql = "SELECT user_id FROM users WHERE user_id = '$user_id' and role = 'Instructor'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);
      if($count == 0)
	  {
		  echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.alert('No such user exists. Please add user before assigning course')
           window.location.href='http://localhost/project/production/new_user.html'
        </SCRIPT>");

	  }

	$sql2 = "SELECT user_id FROM courses Where courses.course_id = '$course_id'";
        $query = mysqli_query($conn,$sql2);
        while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
        $newuserid = $row1['user_id'];
	
		if ($newuserid == $user_id)
		{
			echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   window.alert('Duplicate Entry Detected. Please try again')
        		   window.location.href='http://localhost/project/production/new_course.html'
        		  </SCRIPT>");
		}
	}

	$sql = "INSERT INTO courses (course_id, user_id)
	VALUES ('$course_id','$user_id')";
 

	if (!mysqli_query($conn,$sql)){
  		die('Error: ');
  	}
	
 	include("/opt/lampp/htdocs/project/production/session.php");
	
	$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
      	$query = mysqli_query($conn,$sql5);
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$role = $row['role'];
	$action = "Course added";
	date_default_timezone_set('Asia/Kolkata');
      	$my_date = date("Y-m-d H:i:s");

	$sql4 = "INSERT INTO system_logs (user_id, Role, date_time, action)
      	VALUES ('$user_id','$role' ,'$my_date','$action')";
      	mysqli_query($conn,$sql4);

        echo ("<SCRIPT LANGUAGE='JavaScript'>
             window.alert('Course Added Successfully')
             window.location.href='http://localhost/project/production/new_course.html'
             </SCRIPT>");
	 
mysqli_close($conn)
?>
