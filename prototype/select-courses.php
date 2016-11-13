<?php
$_SESSION['student'] = array();

foreach($_POST as $key=>$val)
{
	$_SESSION['student'][$key] = $val;
}

$myfile = fopen("./wi2016.txt", "r") or die("Unable to open file!");
while (!feof ($myfile)) {
	$array = array();
    $line = fgets($myfile);
	$array = explode(";", $line);
	if(count($array) == 31)
	{
		if(!$classArr[trim($array[4])])
			$classArr[trim($array[4])] = array();
		if(!$classArr[trim($array[4])][trim($array[5])])
			$classArr[trim($array[4])][trim($array[5])] = array();

		$days = trim($array[19]).trim($array[20]).trim($array[21]).trim($array[22]).trim($array[23]).trim($array[24]).trim($array[25]);
		$classArr[trim($array[4])][trim($array[5])][trim($array[7])] = array("start"=>trim($array[17]),
	 		"end"=>trim($array[18]),
	  		"days"=>trim($days));
	}
}
fclose($myfile);
ksort($classArr);
//var_dump($_SESSION['Student']);
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="./CSS/global.css">
	<script src="./JS/jquery-3.1.1.min.js"></script>
</head>
  <body>
    <div id="container">
      <div id="header"><span id="title">Honors Advising Portal</span>
      </div>
    </div>
    <h1>Select Student Courses</h1>
    <div>
			<form action="schedule.php" method="post" id="schedule">
	      <table>
	        <tr>
	          <th>Prefix</th>
	          <th>Course No.</th>
	          <th>Honors</th>
	          <th>CRN</th>
	          <th>Days</th>
	          <th>Time</th>
	        </tr>
	        <tr>
	          <td>
	            <select id="prefix1" onchange="changeCourseNo(1);"name="prefix[]" >
								<option value="">Select</option>
								<?php foreach($classArr as $prefix=>$course_info) { ?>
									<option value="<?=$prefix?>"><?=$prefix?></option>
								<?php } ?>
	            </select>
	          </td>
	          <td>
	            <select id="courseNo1" name ="courseNo[]" disabled>
	              <option value = "">Course No.</option>
	            </select>
	          </td>
	          <td>
	            <select name="honors[]" disabled>
	              <option>Both</option>
	            </select>
	          </td>
	          <td>
	            <select id="crn1" name="crn[]" disabled>
	              <option>CRN</option>
	            </select>
	          </td>
	          <td>
	            <select id="days1" name="days[]" disabled>
	              <option>Day</option>
	            </select>
	          </td>
	          <td>
	            <select id="time1" name="time[]" disabled>
	              <option>Time</option>
	            </select>
	          </td>
	        </tr>
	      </table>
				</form>
	      <div style="margin-top: 10px;">
	        <!--<button onclick="">Add Another Course</button>-->
	        <input type="button" onclick="document.getElementById('schedule').submit();" value="See Schedule(s)"/>
	      </div>
			</div>
    </div>
  </body>

	<script>
		var classArray = JSON.parse('<?=json_encode($classArr)?>');
		function changeCourseNo(number)
		{
			var valSelected = $("#prefix"+number).val();
			if(valSelected != "")
			{
				var sections = classArray[valSelected];
				var sectionKeys = Object.keys(sections);
				var replaceStr = "<option value=''> Select </option> ";
				for(var i = 0; i < sectionKeys.length; i++)
				{
					replaceStr += " <option value='"+sectionKeys[i]+"'>"+sectionKeys[i]+"</option> ";
				}
				$("#courseNo"+1).prop('disabled', false);
				$("#courseNo"+1).html(replaceStr);
			} else {
				$("#courseNo"+1).prop('disabled', "disabled");
			}
		}
		/*$('#prefix1').on('change', function (e) {
	    var opSelected = $("option:selected", this);
	    var valSelected = this.value;
			if(valSelected != "")
			{
				var sections = classArray[valSelected];
				var sectionKeys = Object.keys(sections);
				var replaceStr = "<option value=''> Select </option> ";
				for(var i = 0; i < sectionKeys.length; i++)
				{
					replaceStr += " <option value='"+sectionKeys[i]+"'>"+sectionKeys[i]+"</option> ";
				}
				$("#courseNo1").prop('disabled', false);
				$("#courseNo1").html(replaceStr);
			} else {
				$("#courseNo1").prop('disabled', "disabled");
			}
		});*/

		$('#courseNo1').on('change', function (e) {
	    var opSelected = $("option:selected", this);
	    var valSelected = this.value;
			var courseSelected = $("#prefix1").val();
			if(valSelected != "")
			{
				var sections = classArray[courseSelected][valSelected];
				var sectionKeys = Object.keys(sections);
				var replaceStr = "<option value=''> Select </option> ";
				for(var i = 0; i < sectionKeys.length; i++)
				{
					replaceStr += " <option value='"+sectionKeys[i]+"'>"+sectionKeys[i]+"</option> ";
				}
				$("#crn1").prop('disabled', false);
				$("#crn1").html(replaceStr);
			} else {
				$("#crn1").prop('disabled', "disabled");
			}
		});

		$('#crn1').on('change', function (e) {
	    var opSelected = $("option:selected", this);
	    var valSelected = this.value;
			var courseSelected = $("#prefix1").val();
			var noSelected = $("#courseNo1").val();
			if(valSelected != "")
			{
				var sections = classArray[courseSelected][noSelected][valSelected];
				var sectionKeys = Object.keys(sections);
				var dayStr = "<option value=''> Select </option> ";
				dayStr += "<option value='"+sections["days"]+"'>"+sections["days"]+"</option>";

				var timeStr = "<option value=''> Select </option> ";
				timeStr += "<option value='"+sections["start"]+"-"+sections['end']+"'>"+sections["start"]+"-"+sections['end']+"</option>";

				$("#days1").prop('disabled', false);
				$("#time1").prop('disabled', false);
				$("#days1").html(dayStr);
				$("#time1").html(timeStr);
			} else {
				$("#days1").prop('disabled', "disabled");
				$("#time1").prop('disabled', "disabled");
			}
		});
	</script>
</html>
