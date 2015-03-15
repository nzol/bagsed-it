<?php

session_start();
include('globalfunctions.php');

if(validateToken()) {


	$q = $_GET['q'];

	$getFeatureResults = $Mysql->query("SELECT * FROM search_result_features WHERE Keyword='$q'");

	echo "<strong>Search results for '" . $q . "'</strong><br><br>";

	while($rsFeatureRes = $getFeatureResults->fetch_assoc()) {
		echo $rsFeatureRes['HTMLData'] . "<br><hr>";
	}


}

?>