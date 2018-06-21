<?php
	include("/opt/lampp/htdocs/project/prod/session.php");
	 //session_start();
	if(isset($_POST['submit']))
		{
		if($con = mysqli_connect('localhost','root','','mooc'))
		{
			$filetemp = $_FILES['fileToUpload']['tmp_name'];
			$filename = $_FILES['fileToUpload']['name'];
			$filetype = $_FILES['fileToUpload']['type'];
			$filepath = "/home/purav/Downloads/".$filename;
			$export = str_replace(".bson","","$filepath");
			$pyvar = str_replace(".bson",".csv","$filepath");
			
			$file=escapeshellarg($filepath);
			$ip = $_SERVER['REMOTE_ADDR'];
			$file1=explode(".",$filename);
			$ext=$file1[1];
			$allowed=array("bson");
			
			$file=putenv("FILENAME=".$filename);	
				
			if(in_array($ext,$allowed))
			{

				$export = str_replace(".bson","","$filepath");
				$pyvar = str_replace(".bson",".csv","$filepath");
				$file=escapeshellarg($filepath);

				move_uploaded_file($filetemp,$filepath);
				$oldldpath = getenv('LD_LIBRARY_PATH');
				putenv("LD_LIBRARY_PATH=");
				exec("sh /opt/lampp/htdocs/project/prod/import.sh $file 2>&1", $output, $return);
				if($return !== 0)
				{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   		fwindow.alert('There was some error while importing bson dump')
					window.location.href='http://localhost/project/prod/form_upload.html'
					</SCRIPT>");
					$action = "Error:importing file";
					date_default_timezone_set('Asia/Kolkata');
      					$my_date = date("Y-m-d H:i:s");

					$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
	      				$query = mysqli_query($conn,$sql5);
					$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
					$role = $row['role'];

					$sql2 = "INSERT INTO system_logs (user_id, Role, date_time, action)
	      				VALUES ('$user_id','$role' ,'$my_date','$action')";
	      				mysqli_query($con,$sql2);
					exit();
				}
				exec("sh /opt/lampp/htdocs/project/prod/export.sh $export 2>&1", $output, $return);
				if($return !== 0)
				{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   		window.alert('There was some error while exporting to csv file')
					window.location.href='http://localhost/project/prod/form_upload.html'
					</SCRIPT>");
					$action = "Error:exporting file";
					date_default_timezone_set('Asia/Kolkata');
      					$my_date = date("Y-m-d H:i:s");

					$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
	      				$query = mysqli_query($conn,$sql5);
					$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
					$role = $row['role'];

					$sql2 = "INSERT INTO system_logs (user_id, Role, date_time, action)
	      				VALUES ('$user_id','$role' ,'$my_date','$action')";
	      				mysqli_query($con,$sql2);
					exit();
				}
				exec("python /opt/lampp/htdocs/project/prod/csvfileseparator.py $pyvar $filename 2>&1", $output, $return);
				if($return !== 0)
				{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   		window.alert('There was some error while separating into multiple csv files')
					window.location.href='http://localhost/project/prod/form_upload.html'
					</SCRIPT>");
					$action = "Error:file separating";
					date_default_timezone_set('Asia/Kolkata');
      					$my_date = date("Y-m-d H:i:s");

					$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
	      				$query = mysqli_query($conn,$sql5);
					$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
					$role = $row['role'];

					$sql2 = "INSERT INTO system_logs (user_id, Role, date_time, action)
	      				VALUES ('$user_id','$role' ,'$my_date','$action')";
	      				mysqli_query($con,$sql2);
					exit();
				}
				
				putenv("LD_LIBRARY_PATH=$oldldpath");
				#echo "bson to csv converted";
				$query = "INSERT INTO bson_file(name,path,type,ip) VALUES('$filename','$filepath','$filetype','$ip')";
				$ros = mysqli_query($con,$query);

				$action = $filename. " uploaded";
				date_default_timezone_set('Asia/Kolkata');
      				$my_date = date("Y-m-d H:i:s");

				$sql5 = "SELECT role FROM users Where users.user_id = '$user_id'";
      				$query = mysqli_query($conn,$sql5);
				$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
				$role = $row['role'];

				$sql2 = "INSERT INTO system_logs (user_id, Role, date_time, action)
      				VALUES ('$user_id','$role' ,'$my_date','$action')";
      				mysqli_query($con,$sql2);
				#echo "file uploaded in db";
				echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   	window.alert('File uploaded Successfully.')
        		   	window.location.href='http://localhost/project/prod/form_upload.html'
        		   	</SCRIPT>");
	
			
			}
			else
			{
				echo ("<SCRIPT LANGUAGE='JavaScript'>
        		   	window.alert('There was some error in file or while uploading. Please upload only bson.')
        		   	window.location.href='http://localhost/project/prod/form_upload.html'
        		   	</SCRIPT>");
			}
		}
}	
		
	
?>
</body>
</html>
