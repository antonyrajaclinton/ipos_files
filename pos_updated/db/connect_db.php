<?php
$base_url="http://localhost/POS/";
try
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "ipos";

	$conn = new mysqli($servername, $username, $password, $dbname);
    //$pdo = new PDO('mysql:host='.$servername.';dbname='.$dbname.','.$username.','.$password);
    $pdo = new PDO("mysql:host=$servername;dbname=".$dbname, $username, $password);

}
catch(PDOException $error)
{
    echo $error->getmessage();
}
?>