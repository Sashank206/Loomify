<?php
$host ="localhost";
$user ="root";
$password= "";
$dbname = "loomify";

$conn  =mysqli_connect($host,$user,$password,$dbname);

if(!$conn){

	echo mysqli_connect_error($conn);
}
?> 
