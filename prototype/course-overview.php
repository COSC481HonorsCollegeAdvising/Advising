<html>
<?php
	include("sensitive.php");
	include("header.php");
?>
<<<<<<< HEAD
=======
<head>
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.css">
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.min.css">
  <link rel="stylesheet" type="text/css" href="./CSS/global.css">
	<script src="./JS/jquery-3.1.1.min.js"></script>
	<script src="course-overview-helper-funcs.js"></script>
</head>
  <body>
    <div id="container" class="row">
      <div id="header"><span id="title">Honors Advising Portal</span>
      </div>
    </div>
>>>>>>> d613e03d734c992a119aba5f908359e07c61a314
    <h1>Course Overview</h1>
    <div>
      <table>
        <tr>
          <th>Prefix</th>
          <th>Course No.</th>
					<!--<th>Advisor</th>-->
          <th>Honors</th>
          <th>CRN</th>
        </tr>
        <tr>
          <td>
<<<<<<< HEAD
            <select id="course_prefix_select" onchange="fetch_course_numbers()">
=======
            <select 	id="course_prefix_select" onchange="fetch_both_honors_and_course_numbers()">
>>>>>>> d613e03d734c992a119aba5f908359e07c61a314
              <option>ACC</option>
            </select>
          </td>
          <td>
            <select id="course_number_select">
              <option>-Select-</option>
            </select>
          </td>
					<!--<td>
            <select id="advisor_select">
              <option>-Select-</option>
            </select>
          </td>-->
          <td>
            <select id="honors_select">
              <option>-Both-</option>
            </select>
          </td>
          <td>
            <select>
              <option>-Select-</option>
            </select>
          </td>
        </tr>
      </table>
			<div id="options" style="margin-top: 10px;">
				<!--<button id="reset_filters_button"> Reset Filters </button>-->
				<button id="submit" onclick="fetch_and_fill_table()"> Submit </button>
			</div>
      <div class="page">
        <span><b>Selected Courses</b></span>
        <table id="course_info_table" style="margin-top: 10px;">
          <tr>
            <th>Prefix</th>
            <th>Course No.</th>
            <th>CRN</th>
            <th>Honors</th>
            <th>Advised</th>
            <th>Registered</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
        </table>
      </div>
      <div style="margin-top: 10px;">
        <button onclick="window.location.href='home.php'">Home</button>
      </div>
    </div>

    <script>
			fetch_course_prefixes();
    </script>

  </body>
</html>
