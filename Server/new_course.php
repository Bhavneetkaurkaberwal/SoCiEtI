<?php
   include("config.php");
   //include("/opt/lampp/htdocs/project/prod/session.php");
  
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
     
  	for ($i= 0; $i < 10; $i++)
        	$array[$i] = "NULL";
    
   	if (!$conn)
	  {
		die('Could not connect: ' . mysql_error());
	  }
    	$j=0;
    	foreach( $_POST as $user_id ) {
    		if( is_array( $user_id ) ) {
        		foreach( $user_id as $instructor ) {
           			$array[$j] = $instructor;
        			$j = $j+1;
      				$sql = "SELECT user_id FROM users WHERE user_id = '$instructor' and role = 'Instructor'";
      				$result = mysqli_query($conn,$sql);
      				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      				$count = mysqli_num_rows($result);
      				if($count == 0)
	  			{
		  			echo ("<SCRIPT LANGUAGE='JavaScript'>
          				window.alert('Instructor ' +$j+ ' does not exists. Please add instructor before assigning course')
           				window.location.href='http://localhost/project/prod/add_course.php'
        				</SCRIPT>");
   					exit(1);
	  			}


  
        		}
     		}
  
  
 	}
  
      $course_id = mysqli_real_escape_string($conn,$_POST['course_id']); 
      $start_date =  mysqli_real_escape_string($conn,$_POST['start_date']);
      $end_date =  mysqli_real_escape_string($conn,$_POST['end_date']);
     }
	
	$sql2 = "SELECT course_id FROM courses Where course_id = '$course_id'";
        $query = mysqli_query($conn,$sql2);
        while ($row1 = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
        
			echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   window.alert('The Course id exists in the database.')
        		   window.location.href='http://localhost/project/prod/add_course.php'
        		  </SCRIPT>");
		
	}

	

	$sql = "INSERT INTO courses (course_id, start_date, end_date, instructor_1, instructor_2, instructor_3, instructor_4, instructor_5, instructor_6, instructor_7, instructor_8, instructor_9, instructor_10)
	VALUES ('$course_id','$start_date','$end_date','$array[0]','$array[1]','$array[2]','$array[3]','$array[4]','$array[5]','$array[6]','$array[7]','$array[8]','$array[9]')";

 

	if (!mysqli_query($conn,$sql)){
  		die('Error: ');
  	}
	
 	include("/opt/lampp/htdocs/project/prod/session.php");
	
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
             window.location.href='http://localhost/project/prod/add_course.php'
             </SCRIPT>");

       $primary_id = "UPDATE csv_repository cr, courses c
		      SET cr.primary_user_id = c.instructor_1 
		      WHERE c.course_id = cr.course_id AND c.course_id = '$course_id'";
        $query = mysqli_query($conn,$primary_id);
	 
mysqli_close($conn)
?>
