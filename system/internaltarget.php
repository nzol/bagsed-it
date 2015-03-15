<?php

session_start();
include('globalfunctions.php');

if(validateToken()) {

	

	$action = $_GET['Action'];


	switch ($action) {
		
		case "getScriptField":

			$ScriptAlias = $_GET['ScriptAlias'];
			$Field = $_GET['Field'];

			$ScriptFieldVal = getScriptInfo($ScriptAlias,$Field);

			echo $ScriptFieldVal;

		break;


		case "requestScript":

			$ScriptAlias = $_GET['ScriptAlias'];
			$Params = $_GET['Params'];

			/*$ParamString = "str=str2";

			$ParamSplit = explode(";", $Params);
			foreach($ParamSplit as $ParamBlock) {
				$ParamBlockSplit = explode(":",$ParamBlock);
				$ParamName = $ParamBlockSplit[0];
				$ParamValue = $ParamBlockSplit[1];
				$ParamString = $ParamString . "&" . $ParamName . "=" . $ParamValue;
			}*/


			if(checkUserScriptAccess($ScriptAlias)) {
				$getScriptPath = getScriptInfo($ScriptAlias,"Path");
				
				$returnSource = HTTPGet($CurrentURLBase . $getScriptPath,$Params);
				echo $returnSource;
			}
			else if($CurrentUserId=="0") {
				$returnSource = file_get_contents("loginform.php");
				echo $returnSource;
			}
			else {
				echo "<ERR>System.InternalTarget.requestScript.AccessDenied";
			}

		break;


		case "requestErrorInfo":

			$ErrCode = $_GET['ErrCode'];
			$Field = $_GET['Field'];

			$getError = $Mysql->query("SELECT * FROM errorcodes WHERE ErrorString='$ErrCode'");
			$rsError = $getError->fetch_assoc();

			$RetVal = $rsError[$Field];

			echo $RetVal;

		break;

		case "signIn":

			$Username = $_GET['Username'];
			$Password = md5($_GET['Password']);

			$getUser = $Mysql->query("SELECT * FROM users WHERE EmailAddress='$Username' AND Password='$Password'");

			if($getUser->num_rows==0) {
				echo "<ERR>System.InternalTarget.signIn.AccessDenied";
			}
			else {
				$rsSession = $getUser->fetch_assoc();
				$newSession = createUserSession($rsSession['Id']);
				$_SESSION['BAGS_currentSessionId']=$newSession;
				$_SESSION['BAGS_currentUserId']=$rsSession['Id'];
				setcookie("BAGS_currentSessionId",$newSession,time()+604800);
				echo $newSession;
			}

		break;

	}





}

else {
	echo "denied, " . $_SESSION['currentSystemToken'];
}

?>