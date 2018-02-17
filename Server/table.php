<?php
$hostname = "localhost";
$user = "root";
$password = "";
$dbname = "mooc";

// Create connection
$conn = mysqli_connect($hostname, $user, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql1 = "CREATE TABLE users (
user_id varchar(20) PRIMARY KEY,
password varchar(20) not null,
role varchar(20) not null
)";

if (mysqli_query($conn, $sql1)) {

    echo nl2br("Table users created successfully \n");

} else {

    echo "Error creating table: " . mysqli_error($conn);

}



$sql2 = "CREATE TABLE system_logs (
session_id int AUTO_INCREMENT PRIMARY KEY,
user_id varchar(20) not null,
Role varchar(10) not null,
date_time datetime,
action varchar(20) not null
)";

if (mysqli_query($conn, $sql2)) {

    echo nl2br("Table system_logs created successfully \n");

} else {

    echo "Error creating table: " . mysqli_error($conn);

}



$sql3 = "CREATE TABLE csv_repository (
course_id varchar(20) PRIMARY KEY,
bson_file varchar(20),
path varchar(20),
primary_user_id varchar(20)
)";

if (mysqli_query($conn, $sql3)) {

    echo nl2br("Table csv_repository created successfully \n");

} else {

    echo "Error creating table: " . mysqli_error($conn);

}


$sql4 = "CREATE TABLE courses (
course_id varchar(20) PRIMARY KEY,
instructor_1 varchar(20) not null,
instructor_2 varchar(20) not null,
instructor_3 varchar(20) not null,
instructor_4 varchar(20) not null,
instructor_5 varchar(20) not null,
instructor_6 varchar(20) not null,
instructor_7 varchar(20) not null,
instructor_8 varchar(20) not null,
instructor_9 varchar(20) not null,
instructor_10 varchar(20) not null
)";

if (mysqli_query($conn, $sql4)) {

    echo nl2br("Table courses created successfully \n");

} else {

    echo "Error creating table: " . mysqli_error($conn);

}


$sql5 = "CREATE TABLE bson_file (
id varchar(20) PRIMARY KEY,
name varchar(20) not null, 
path varchar(20) not null,
type varchar(20) not null,
ip varchar(20) not null
)";

if (mysqli_query($conn, $sql5)) {

    echo nl2br("Table csv_files created successfully \n");

} else {

    echo "Error creating table: " . mysqli_error($conn);

}



mysqli_close($conn);
?>
