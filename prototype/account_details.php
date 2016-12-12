<?php
session_start();

//check for login
if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
{
	header("Location: login.php");
	die();
}

include("header.php");
 ?>

<div class="row" style="margin-top: 25px;">
  <div class="large-6 large-centered columns">
    <h3>User Account Information</h3>
  </div>
</div>
<div class="row" style="margin-top: 10px;">
  <div class="large-8 large-centered columns">
    <div class="row">
      <div class="large-6 columns">
        <span>Name: <?=$_SESSION['user']['fname']." ".$_SESSION['user']['lname']?></span>
      </div>
      <div class="large-6 columns">
        <span>Emich: <?=$_SESSION['user']['netID']."@emich.edu"?></span>
      </div>
    </div>
  </div>
</div>
<div class="row" style="margin-top: 25px;">
  <div class="large-6 large-centered columns">
    <span>Change Password</span>
  </div>
</div>
  <div class="row" style="margin-top: 10px;">
    <div class="large-4 large-centered columns" style="text-align: left">
      <label> <b>Current Password:</b> </label>
      <input type="password" id="curpass" name="curpass"/>
    </div>
  </div>
  <div class="row">
    <div class="large-4 large-centered columns" style="text-align: left">
    <label> <b>New Password:</b> </label>
      <input type="password" id="newpass" name="newpass"/>
    </div>
  </div>
  <div class="row">
    <div class="large-4 large-centered columns" style="text-align: left">
      <label> <b>Confirm Password:</b> </label>
      <input type="password" id="newpass_2" name="newpass_2" />
    </div>
  </div>
  <div style="text-align: center; padding-top: 5px;" >
      <button onClick="validatePassword();">Submit</button>
  </div>

<script>
  function validatePassword()
  {
    var currentPassword = $("#curpass").val();
    var newPassword = $("#newpass").val();
    if(newPassMatch())
    {
      $.ajax({
        method: "POST",
        url: "password_funcs.php",
        data: {action:"changePassword", currentPassword:currentPassword, newPassword:newPassword},
        success: function(output) {
          if(output == 1) {
            alert("Password changed successfully!");
            location.reload();
          } else {
            alert(output);
          }
        }
      });
    }
  }
  function newPassMatch()
  {
    if($("#newpass").val() == $("#newpass_2").val()) return true;
    else return false;
  }
</script>
