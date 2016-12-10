<?php
session_start();
session_unset();
if($_POST['netID'] && $_POST['password'])
{
  include("sensitive.php");

  // Check connection
  if (mysqli_connect_errno()) {
      die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT *
            FROM ADVISOR
            WHERE advisorNetID = '".$_POST['netID']."'";

  $result = mysqli_query($conn, $query);
  $row=mysqli_fetch_assoc($result);

  if(password_verify($_POST['password'], $row['hashedPassword']))
  {
    session_start();
    $_SESSION['user']['netID'] = $row['advisorNetID'];
    $_SESSION['user']['fname'] = $row['firstName'];
    $_SESSION['user']['lname'] = $row['lastName'];
    $_SESSION['user']['isAdmin'] = $row['isAdmin'];

    header("Location: home.php");
    die();
  } else {
    //give some false error here
  }
} else if($_POST['netID'] || $_POST['password']){
  //tell user password/username combo failed.
} else {
  //clear pre-existing session variables here.
}
include("header.php");
?>
	<div id="pagebody" class="row">
		<form action="login.php" method="post">
		  <div id="login" class="large-offset-4 large-4 columns">
			<div style="text-align: center;">
			  <span><b>Login</b></span>
			</div>
			<p>NetID</p>
			<input type="text" name="netID"/>
			<p>Password</p>
			<input type="password" name="password" style="margin-bottom: 0px;"/><br/>
      <a href="forgot-password.php">Forgotten Password?</a>
			<div style="text-align: center; padding-top: 20px;">
			  <button type="submit" value="Submit">Submit</button>
			</div>
		  </div>
		</form>
	</div>
  </body>
</html>
