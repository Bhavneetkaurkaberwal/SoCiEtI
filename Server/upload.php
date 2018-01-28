<?php
	if(isset($_POST['submit']))
	{
		if($con = mysqli_connect('localhost','root','','mooc'))
		{	
			// Gets information of uploaded file
			$filetemp = $_FILES['fileToUpload']['tmp_name'];
			$filename = $_FILES['fileToUpload']['name'];
			$filetype = $_FILES['fileToUpload']['type'];
			$filepath = "C:/Users/DELL/Downloads/".$filename;
			$ip = $_SERVER['REMOTE_ADDR'];
			
			//checks file extension (bson)
			$file1=explode(".",$filename);
			$ext=$file1[1];
			$allowed=array("csv","bson");
			
			//stores information of uploaded file in Database
			if(in_array($ext,$allowed))
			{
				move_uploaded_file($filetemp,$filepath);
				$query = "INSERT INTO csv(name,path,type,ip) VALUES('$filename','$filepath','$filetype','$ip')";
				$ros = mysqli_query($con,$query);
				echo "file uploaded in db";
			
			}
			else
			{
				echo "Upload only Bson file";
				echo "<br />";
				echo '<a href="upload.php">Click here</a> to go back and try again';
			}
			
		}
	}
?>
