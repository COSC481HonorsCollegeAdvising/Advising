<?php
include("sensitive.php");

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

$action = $_POST['action'];

switch($action) {
  case 'status':
    $admin = $_POST['admin'];
    $netID = $_POST['netID'];
    $query = "UPDATE ADVISOR
              SET isAdmin=".$admin."
              WHERE advisorNetID='".$netID."'";

    mysqli_query($conn, $query);
    echo true;
    break;
  case 'addAdvisor':
    $fname = mysqli_real_escape_string($conn, strip_tags($_POST['fname']));
    $lname = mysqli_real_escape_string($conn, strip_tags($_POST['lname']));
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_BCRYPT);
    $netID = mysqli_real_escape_string($conn, strip_tags($_POST['netID']));

    $query = "INSERT INTO ADVISOR
              VALUES ('".$netID."', '".$fname."',
              '".$lname."', 0,'".$password."')";
    //$query = mysqli_real_escape_string($conn, strip_tags($query));

    if(!$result = mysqli_query($conn, $query))
    {
      echo("Error description: " . mysqli_error($conn));
    } else echo true;
    break;

  case 'removeAdvisor':
    $netID = $_POST['netID'];
    $query = "DELETE FROM ADVISOR
              WHERE advisorNetID='".$netID."'";

    mysqli_query($conn, $query);
    echo true;
    break;
}



 ?>
