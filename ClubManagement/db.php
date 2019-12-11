<?php
	$servername = "localhost";
	$db_username = "system";
	$db_password = "system";
	
	$tns = " 
	(DESCRIPTION =
		(ADDRESS_LIST =
		  (ADDRESS = (PROTOCOL = TCP)(HOST = $servername)(PORT = 1521))
		)
		(CONNECT_DATA =
		  (SERVICE_NAME = XE)
		)
	 )
		   ";
	try{
		$conn = new PDO("oci:dbname=".$tns, $db_username, $db_password);
	} catch(PDOException $e){
		echo ($e->getMessage());
	}	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//$conn = oci_connect($db_username, $db_password, 'localhost/XE');
?>


 