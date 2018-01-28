
<?php
//connection to db
$con=mysqli_connect('localhost','root','','demo');
$query=mysqli_query($con,"select * from csv");
$rowcount=mysqli_num_rows($query);
?>
<p>Download</p>
<?php
//retrieve files from database 
for($i=1;$i<=$rowcount;$i++)
{
	$row=mysqli_fetch_array($query);
?>
<!--display csv files to download-->
<a href="csvfiles/<?php echo $row["name"]?>"><?php echo $row["name"] ?><br></a>
<?php } ?>

