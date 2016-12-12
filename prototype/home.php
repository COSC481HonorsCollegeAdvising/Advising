<?php
  session_start();

  if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
  {
  	header("Location: login.php");
  	die();
  }

  include("header.php");
 ?>

    <div id="container" class="row">
	     <div class="large-4 columns"><h4>Hello, <?=$_SESSION['user']['fname']?></h4></div>
    </div>
	<div id="container" class="row">
    <?php if ($_SESSION['user']['isAdmin'] == 1)
    { ?>
		<div class="large-3 columns home-page-items">
		  <div style="text-align: center;">
			<span><b>Advising Session</b></span>
		  </div>
		  <div style="text-align: center; padding-top: 5px;">
			<button onclick="window.location.href='student-info.php'">Start</button>
		  </div>
		</div>
		<div class="large-6 columns home-page-items">
		  <div style="text-align: center;">
			<span><b>Detailed Course Enrollment Information</b></span>
		  </div>
		  <div style="text-align: center; padding-top: 5px;">
			<button onclick="window.location.href='course-overview.php'">Continue</button>
		  </div>
		</div>
		<div class="large-3 columns home-page-items">
		  <div style="text-align: center;">
			<span><b>Manage Users</b></span>
		  </div>
		  <div style="text-align: center; padding-top: 5px;">
			<button onclick="window.location.href='users.php'">Manage</button>
		  </div>
		</div>
    <?php } else { ?>
      <div class="large-6 columns home-page-items">
  		  <div style="text-align: center;">
  			<span><b>Advising Session</b></span>
  		  </div>
  		  <div style="text-align: center; padding-top: 5px;">
  			<button onclick="window.location.href='student-info.php'">Start</button>
  		  </div>
  		</div>
  		<div class="large-6 columns home-page-items">
  		  <div style="text-align: center;">
  			<span><b>Detailed Course Enrollment Information</b></span>
  		  </div>
  		  <div style="text-align: center; padding-top: 5px;">
  			<button onclick="window.location.href='course-overview.php'">Continue</button>
  		  </div>
  		</div>
    <?php } ?>
	 </div>
	 <div id="container" class="row">

	 </div>
   <script>
     $(document).foundation();
   </script>
  </body>
</html>
