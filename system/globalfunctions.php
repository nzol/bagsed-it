<?php

$Mysql = new Mysqli("localhost","remote","remote","bagsedit");

if(isset($_SESSION['BAGS_currentUserId'])) {
	$CurrentUserId = $_SESSION['BAGS_currentUserId'];
}
else {
	$CurrentUserId = "0";
}

$CurrentURLBase = "http://localhost/bagsed-it/";

// System Sessions & Tokens

function checkSystemSession() {
	global $Mysql;
	if(isset($_SESSION['BAGS_currentSystemToken'])) {
		return "fine";
	}
	else {
		$newToken = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0011123434656787890"),0,15);
		$_SESSION['BAGS_currentSystemToken']=$newToken;
		$addToken = $Mysql->query("INSERT INTO system_tokens (TokenString) VALUES ('$newToken')");
	}
}


function validateToken() {
	$TokenString = $_SESSION['BAGS_currentSystemToken'];
	global $Mysql;
	$getToken = $Mysql->query("SELECT * FROM system_tokens WHERE TokenString='$TokenString'");
	if($getToken->num_rows==0) {
		return false;
	}
	else {
		return true;
	}
}

// Scripts

function getScriptInfo($ScriptAlias,$Field) {

	global $Mysql;

	$getScript = $Mysql->query("SELECT * FROM scripts WHERE Alias='$ScriptAlias'");
	$rsScript = $getScript->fetch_assoc();

	$RetVal = $rsScript[$Field];

	return $RetVal;

}

// Rights

function userHoldsRight($RightId) {

	global $Mysql;
	global $CurrentUserId;

	$getUserRight = $Mysql->query("SELECT * FROM rights_assigned WHERE UserId='$CurrentUserId' AND RightId='$RightId'");

	if($getUserRight->num_rows==0) {
		return false;
	}
	else {
		return true;
	}
}

function checkUserScriptAccess($ScriptAlias) {

	global $Mysql;

	$getScriptRight = $Mysql->query("SELECT * FROM scripts WHERE Alias='$ScriptAlias'") or die($Mysql->error);
	$rsScriptRight = $getScriptRight->fetch_assoc();

	$ScriptRight = $rsScriptRight['RightId'];

	if(userHoldsRight($ScriptRight)) {
		return true;
	}
	else {
		return false;
	}

}


function createUserSession($UserId) {

	global $Mysql;

	$SessionToken = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123457689"),0,31);

	$addSession = $Mysql->query("INSERT INTO user_sessions (UserId,SessionToken) VALUES ('$UserId','$SessionToken')");

	return $SessionToken;

}

function HTTPGet($url,$params) {

	$paramssplit = explode(";", $params);
	$buildqueryarr = array();

	foreach($paramssplit as $paramline) {
		$param_split = explode(":",$paramline);
		//echo print_r($param_split);
		$buildqueryarr[$param_split[0]]=$param_split[1];
	}


	$postdata = http_build_query($buildqueryarr);

	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);

	$context  = stream_context_create($opts);

	$result = file_get_contents($url, false, $context);

	return $result;

}


?>