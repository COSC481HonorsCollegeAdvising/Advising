<?php session_start();

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['action']) && $_POST['action'] != "")
{
  include("sensitive.php");

  switch($_POST['action'])
  {
    case "changePassword":
      $query = "SELECT *
                FROM ADVISOR
                WHERE advisorNetID = '".$_SESSION['user']['netID']."'";

      $result = mysqli_query($conn, $query);
      $row=mysqli_fetch_assoc($result);

      if(password_verify($_POST['currentPassword'], $row['hashedPassword']))
      {
        $password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
        $query = "UPDATE ADVISOR
                  SET hashedPassword='".$password."'
                  WHERE advisorNetID='".$_SESSION['user']['netID']."'";
        $result = mysqli_query($conn, $query);
        if(!$result)
        {
          echo "Error Message: ".mysqli_error($conn);
        } else echo 1;
      } else {
        echo "Current password did not match. Please try again.";
      }
      break;
    case "forgotPassword":
      //create a new random password with length of 5
      $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
      $string = '';
      $max = strlen($characters) - 1;
      for ($i = 0; $i < 6; $i++) {
           $string .= $characters[mt_rand(0, $max)];
      }
      break;
  }

}
?>
