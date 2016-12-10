<?php
session_start();
session_unset();
$disp_str = "Please enter your netID to proceed.";
if($_POST['netID'] && ($_POST['netID'] == $_POST['netID_2']))
{
  include("sensitive.php");

  // Check connection
  if (mysqli_connect_errno()) {
      die("Connection failed: " . mysqli_connect_error());
  }


  //create a new random password with length of 5
  $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
  $tempPassword = '';
  $max = strlen($characters) - 1;
  for ($i = 0; $i < 6; $i++) {
       $tempPassword .= $characters[mt_rand(0, $max)];
  }

  $password = password_hash($tempPassword, PASSWORD_BCRYPT);
  $query = "UPDATE ADVISOR
            SET hashedPassword='".$password."'
            WHERE advisorNetID='".$_POST['netID']."'";
  $result = mysqli_query($conn, $query);
  if(!$result)
  {
    echo "Error Message: ".mysqli_error($conn);
  } else {
    $disp_str = "New password: ".$tempPassword;
    //quick and dirty email mailer (does not work)
    $to      = $_POST['netID'].'@emich.edu';
    $subject = 'Honors Advising - Forgotten Password';
    $message = 'Hello,\r\n
                It seems you have requested an account password change.\r\n
                Below is your new password. Once you login, please go to account settings and change your password manually.\r\n
                If this email was not activated by you, change your password all the same.\r\n'.
                $tempPassword.'\r\n';
    $message = wordwrap($message, 70, "\r\n");
    $headers = 'From: honors_advising@emich.edu' . "\r\n" .
        'Reply-To: honors_advising@emich.edu' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    if(mail($to, $subject, $message, $headers) != false)
    {
      //echo "Mail should have been sent to: ".$_POST['netID'];
    } else {
      //echo "Error sending mail.";
    }
  }
} else if($_POST['netID']){
  //tell user netIDs didnt match
  $disp_str = "Net IDs did not match. Please try again";
} else {
  //tell user to enter an ID
  $disp_str = "Please enter your netID to proceed.";
}
include("header.php");
?>
    <div class="row" style="margin-top: 25px;">
      <div class="large-4 columns large-centered">
        <span><?=$disp_str?></span>
      </div>
    </div>
	<div id="pagebody" class="row">
		<form action="forgot-password.php" method="post">
		  <div id="login" class="large-offset-4 large-4 columns">
			<div style="text-align: center;">
			  <span><b>Forgotten Password</b></span>
			</div>
			<p>NetID</p>
			<input type="text" name="netID"/>
			<p>Confirm NetID</p>
			<input type="text" name="netID_2"/><br/>
			<div style="text-align: center; padding-top: 5px;">
			  <button type="submit" value="Submit">Submit</button>
			</div>
		  </div>
		</form>
	</div>
  </body>
</html>
