<?php session_start();

if(isset($_POST['action']) && $_POST['action'] != ""){
	if($_POST['action'] == "set-crn"){
		if(isset($_POST['crn_data']) && $_POST['crn_data'] != "")
		$_SESSION["CRN"] = $_POST['crn_data'];
		//echo $_SESSION["CRN"];
	}
}

?>
