<?php session_start();

  include("sensitive.php");

  if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
  {
  	header("Location: login.php");
  	die();
  }

  if(isset($_SESSION['student']['eid']) && $_SESSION['student']['eid'] != "")
  {
    //unset the student session
    unset($_SESSION['student']);
  } else {
    header("Location: home.php");
    die();
  }
  include("header.php");
?>
  <div class="row">
    <div class="large-6 large-centered columns">
      <h3>Advising Session Complete!</h3>
    </div>
  </div>
  <div class="row">
      <div class="large-6 large-centered columns">
        <span>The student will receive an email shortly regarding the details of their advised schedule.</span>
      </div>
  </div>
  <div class="row">
    <div class="large-6 large-centered columns" style="margin-top: 10px;">
      <button onclick='window.location.href="home.php"'>Home</button>
    </div>
  </div>
  </body>
</html>
