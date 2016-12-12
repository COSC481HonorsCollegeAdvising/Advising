<?php
  session_start();

  if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
  {
  	header("Location: login.php");
  	die();
  }
  
	include("sensitive.php");
  include("header.php");

?>
  <style>
    td {
      border: none;
    }
  </style>
  <script>
  function validateEID()  {
    var eid = /(E|e)\d{8}/.test(document.getElementsByName('eid')[0].value);
    if (eid)  {
      return true;
    }
    alert("Please enter a valid EID.");
    return false;
  }

  function validateEmail()  {
    var email = document.getElementsByName('emich')[0].value;
    if (/^\w{1,8}@emich\.edu$/.test(email)) {
      email = /^\w{1,8}/.exec(email);
    }

    if (/^\w{1,8}$/.test(email))  {
      return true;
    }
    alert("Please enter a valid netID or emich email.");
    return false;
  }

  function validateStudent() {
    return (validateEmail() && validateEID());
  }
  </script>
    <div id="login" class="row" style="border:none;">
      <div style="text-align: center; padding-bottom: 10px;" class="large-12 columns">
        <span><b>Student Advising Information</b></span>
      </div>
	</div>
	<form action="select-courses.php" method="post" id="student-info" style="text-align:left;">
      <div class="row">
	    <div class="large-offset-4 large-4 columns">
            <label> <b>EID:</b>
            <input type="text" name="eid" placeholder="E00000000"/>
            </label>
		</div>
      </div>
      <div class="row">
	    <div class="large-offset-4 large-4 columns">
			<label> <b>First Name:</b>
            <input type="text" name="fname" placeholder="Jane"/>
            </label>
        </div>
      </div>
      <div class="row">
	    <div class="large-offset-4 large-4 columns">
	      <label> <b>Last Name:</b>
          <input type="text" name="lname" placeholder="Doe"/>
          </label>
        </div>
      </div>
      <div class="row">
	    <div class="large-offset-4 large-4 columns">
          <label> <b>Emich Email:</b>
          <input type="text" name="emich" placeholder="jdoe123"/>
          </label>
        </div>
      </div>
      <div style="text-align: center; padding-top: 5px;" >
          <button type="submit" onClick="return validateStudent();">Continue</button>

        </div>
      </form>
	</div>
  </body>
</html>
