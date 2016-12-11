<html>
<head>
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.css">
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.min.css">
  <link rel="stylesheet" type="text/css" href="./CSS/global.css">
	<script src="./JS/jquery-3.1.1.min.js"></script>
	<script src="section-detail-helper-funcs.js"></script>

</head>
  <body>
    <div id="container" class="row">
      <div id="header"><span id="title">Honors Advising Portal</span>
      </div>
    </div>
    <div>
      <div class="page">
			<span><b>Section Details</b></span>
			<table id="section_detail_table" class="add" style="margin-top: 20px; margin-bottom: 40px;">
			  <tr>
				<th>Prefix</th>
				<th>Course No.</th>
				<th>CRN</th>
				<th>Honors</th>
				<th>Advised</th>
				<th>Registered</th>
				<th>Capacity</th>
			  </tr>
			</table>
        <span><b>Registered Students</b></span>
        <table id="students_advised_table" class="add" style="margin-top: 20px;">
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>EID</th>
            <th>Advisor</th>
            <th>Date</th>
          </tr>
        </table>
      </div>
      <div style="margin-top: 10px;">
        <button onclick="window.location.href='course-overview.php'">Go Back</button>
      </div>
    </div>

		<script>
			fetch_section_details();
			fetch_students_advised_for_section();
		</script>

  </body>
</html>
