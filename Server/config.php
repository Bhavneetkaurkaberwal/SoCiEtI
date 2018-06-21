<?php
   $conn= mysqli_connect("localhost","root","") or die ("could not connect to mysql"); 
   mysqli_select_db($conn, "mooc") or die ("no database");   
?>
