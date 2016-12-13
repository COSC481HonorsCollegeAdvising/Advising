<?php
session_start();

//check for login
if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
{
	header("Location: login.php");
	die();
}
foreach($_POST as $key=>$val)
{
	$_SESSION['student'][$key] = $val;
}

if(!isset($_SESSION['student']['eid']) || $_SESSION['student']['eid'] == "")
{
	header("Location: student-info.php");
	die();
}
include("sensitive.php");

// Check connection
if (mysqli_connect_errno()) {
		die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT DISTINCT coursePrefix
					FROM COURSE";
$result = mysqli_query($conn, $query);

while ($row=mysqli_fetch_assoc($result))
{
	$coursePrefixes[] = $row['coursePrefix'];
}
sort($coursePrefixes, SORT_STRING);

include("header.php");
?>
	<style>
		select {
			margin-right: 20px;
		}
	</style>
		<div class="row" style="padding-top: 25px;">
			<div class="large-6 columns large-centered">
    		<h3>Select Student Courses</h3>
			</div>
		</div>
		<div class="row" style="padding-top: 10px; color: #545454">
			<div class="large-12 columns large-centered">
				<span><i>To get the possible course combinations, please enter in
				the prefix and course number for every course to advise.</i></span>
			</div>
		</row>
		<div class="row" style="padding-top: 15px;">
	    <div class="large-12 columns">
				<form action="schedule.php" method="post" id="schedule">
		      <table>
		        <tr>
		          <th>Prefix</th>
		          <th>Course No.</th>
		          <th>Honors</th>
		          <th>CRN</th>
		          <th>Days</th>
		          <th>Time</th>
							<th>Credits</th>
		        </tr>
		        <tr id="row1">
		          <td>
		            <select id="prefix1" onchange="changeCourseNo(1);" name="prefix[]" >
									<option value="">Select</option>
									<!--<?php foreach($classArr as $prefix=>$course_info) { ?>
										<option value="<?=$prefix?>"><?=$prefix?></option>
									<?php } ?>-->

									<?php foreach($coursePrefixes as $key=>$prefix) {?>
										<option value="<?=$prefix?>"><?=$prefix?></option>
									<?php } ?>
		            </select>
		          </td>
		          <td>
		            <select id="courseNo1" onchange="changeCRN(1);" name ="courseNo[]" disabled>
		              <option value = "">Course No.</option>
		            </select>
		          </td>
		          <td>
		            <select id="honors1" onchange="changeOnHonors(1);" name="honors[]" disabled>
		              <option value = "">Both</option>
									<option value= "1">Yes</option>
									<option value= "0">No</option>
		            </select>
		          </td>
		          <td>
		            <select id="crn1" onchange="changeOnCRN(1);" name="crn[]" disabled>
		              <option value="">CRN</option>
		            </select>
		          </td>
		          <td>
		            <select id="days1" onchange="changeOnDays(1)" name="days[]" disabled>
		              <option value="">Day</option>
		            </select>
		          </td>
		          <td>
		            <select id="time1" onchange="changeOnTime(1)" name="time[]" disabled>
		              <option value="">Time</option>
		            </select>
		          </td>
							<td>
		            <select id="credits1" onchange="changeOnCredits(1)" name="credits[]" disabled>
		              <option value="">Credits</option>
		            </select>
		          </td>
		        </tr>
		      </table>
				</form>
			</div>
		</div>
		<div class="row">
	      <div style="margin-top: 10px;">
	        <button onclick="addCourseLine();">Add Another Course</button>
					<button onclick="removeCourseLine();">Remove Last Course</button>
	        <button onclick="validatePreSuf();">See Schedules</button>
	      </div>
		</div>
  </body>

	<script>
		var prefixArr = JSON.parse("<?=addslashes(json_encode($coursePrefixes))?>");
		$(document).ready(function() {
			$(document).foundation();
			$("#prefix1").val("");
			$("#courseNo1").val("");
			$("#courseNo1").prop('disabled', "disabled");
			$("#crn1").val("");
			$("#crn1").prop('disabled', "disabled");
			$("#days1").val("");
			$("#days1").prop('disabled', "disabled");
			$("#time1").val("");
			$("#time1").prop('disabled', "disabled");
			$("#honors1").val("");
			$("#honors1").prop('disabled', "disabled");
			$("#credits1").val("");
			$("#credits1").prop('disabled', "disabled");
		});
		var classArray = JSON.parse('<?=json_encode($classArr)?>');
		var courses = 1;

		function validatePreSuf() {
			var prefixes = document.getElementsByName("prefix[]");
			var courses = document.getElementsByName("courseNo[]");
			var valid = true;
			for(var i = 0; i < prefixes.length; i++)
			{
				if(prefixes[i].value == "" || courses[i].value == ""){
					 valid = false;
					 alert("Please enter a prefix and course number for every course.");
					 break;
				 }
			}
			if(valid)
				document.getElementById('schedule').submit();
		}

		function changeCourseNo(number)
		{
			var valSelected = $("#prefix"+number).val();
			if(valSelected != "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: {action: "prefix", prefix: valSelected},
	        success: function(output) {
	          if(output != 0)
	          {
							var courseNoArr = JSON.parse(output);
							var replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseNoArr.length; i++)
							{
								replaceStr += " <option value='"+courseNoArr[i]+"'>"+courseNoArr[i]+"</option> ";
							}
							$("#courseNo"+number).prop('disabled', false);
							$("#courseNo"+number).html(replaceStr);

							$("#crn"+number).val("");
							$("#crn"+number).prop('disabled', "disabled");
							$("#days"+number).val("");
							$("#days"+number).prop('disabled', "disabled");
							$("#time"+number).val("");
							$("#time"+number).prop('disabled', "disabled");
							$("#honors"+number).val("");
							$("#honors"+number).prop('disabled', "disabled");
							$("#credits"+number).val("");
							$("#credits"+number).prop('disabled', "disabled");
	          } else {
	            alert("Error in recieving data");
	          }
	        }
	      });
			} else {
				$("#courseNo"+number).val("");
				$("#courseNo"+number).prop('disabled', "disabled");
				$("#crn"+number).val("");
				$("#crn"+number).prop('disabled', "disabled");
				$("#days"+number).val("");
				$("#days"+number).prop('disabled', "disabled");
				$("#time"+number).val("");
				$("#time"+number).prop('disabled', "disabled");
				$("#honors"+number).val("");
				$("#honors"+number).prop('disabled', "disabled");
				$("#credits"+number).val("");
				$("#credits"+number).prop('disabled', "disabled");
			}
		}

		function changeCRN(number) {
			var valSelected = $("#courseNo"+number).val();
			var courseSelected = $("#prefix"+number).val();
			if(valSelected != "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: {action: "courseNO", prefix: courseSelected, courseNO: valSelected},
	        success: function(output) {
	          if(output != 0)
	          {
							var courseInfoArr = JSON.parse(output);
							//Replace days
							var replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['days'].length; i++)
							{
								if(courseInfoArr['days'][i] != "")
									replaceStr += " <option value='"+courseInfoArr['days'][i]+"'>"+courseInfoArr['days'][i]+"</option> ";
							}
							$("#days"+number).prop('disabled', false);
							$("#days"+number).html(replaceStr);

							//replace times
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['times'].length; i++)
							{
								if(courseInfoArr['times'][i] != "12:00 am - 12:00 am")
									replaceStr += " <option value='"+courseInfoArr['times'][i]+"'>"+courseInfoArr['times'][i]+"</option> ";
							}
							$("#time"+number).prop('disabled', false);
							$("#time"+number).html(replaceStr);

							//replace CRNs
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['crns'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['crns'][i]+"'>"+courseInfoArr['crns'][i]+"</option> ";
							}
							$("#crn"+number).prop('disabled', false);
							$("#crn"+number).html(replaceStr);

							//replace honors
							replaceStr = "<option value=''> Both </option> ";
							for(var i = 0; i < courseInfoArr['isHonors'].length; i++)
							{
								if(courseInfoArr['isHonors'][i] == 1)
								{
									var honors_str = "Yes";
								} else {
									var honors_str = "No";
								}
								replaceStr += " <option value='"+courseInfoArr['isHonors'][i]+"'>"+honors_str+"</option> ";
							}
							$("#honors"+number).prop('disabled', false);
							$("#honors"+number).html(replaceStr);

							//replace credits
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['credits'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['credits'][i]+"'>"+courseInfoArr['credits'][i]+"</option> ";
							}
							$("#credits"+number).prop('disabled', false);
							$("#credits"+number).html(replaceStr);
	          } else {
	            alert("Error in recieving data");
	          }
	        }
				});
			} else {
				$("#crn"+number).val("");
				$("#crn"+number).prop('disabled', "disabled");
				$("#days"+number).val("");
				$("#days"+number).prop('disabled', "disabled");
				$("#time"+number).val("");
				$("#time"+number).prop('disabled', "disabled");
				$("#honors"+number).val("");
				$("#honors"+number).prop('disabled', "disabled");
				$("#credits"+number).val("");
				$("#credits"+number).prop('disabled', "disabled");
			}
		}

		function changeOnCRN(number) {
			var valSelected = $("#crn"+number).val();
			var courseSelected = $("#prefix"+number).val();
			var noSelected = $("#courseNo"+number).val();
			var daysSelected = $("#days"+number).val();
			var timeSelected = $("#time"+number).val();
			var honorsSelected = $("#honors"+number).val();
			var creditsSelected = $("#credits"+number).val();
			var ajax_data = {action: "CRN", prefix: courseSelected, courseNO: noSelected, CRN: valSelected};
			if(timeSelected != "")
				ajax_data.times = timeSelected;
			if(daysSelected != "")
				ajax_data.days = daysSelected;
			if(honorsSelected != "")
				ajax_data.isHonors = honorsSelected;
			if(creditsSelected != "")
				ajax_data.credits = creditsSelected;
			$.ajax({
        method: "POST",
        url: "course_select_funcs.php",
        data: ajax_data,
        success: function(output) {
          if(output != 0)
          {
						var courseInfoArr = JSON.parse(output);
						//Replace days
						var replaceStr = "<option value=''> Select </option> ";
						for(var i = 0; i < courseInfoArr['days'].length; i++)
						{
							if(courseInfoArr['days'][i] != "")
								replaceStr += " <option value='"+courseInfoArr['days'][i]+"'>"+courseInfoArr['days'][i]+"</option> ";
						}
						$("#days"+number).prop('disabled', false);
						$("#days"+number).html(replaceStr);
						$("#days"+number).val(daysSelected);
						//replace times
						replaceStr = "<option value=''> Select </option> ";
						for(var i = 0; i < courseInfoArr['times'].length; i++)
						{
							if(courseInfoArr['times'][i] != "12:00 am - 12:00 am")
								replaceStr += " <option value='"+courseInfoArr['times'][i]+"'>"+courseInfoArr['times'][i]+"</option> ";
						}
						$("#time"+number).prop('disabled', false);
						$("#time"+number).html(replaceStr);
						$("#time"+number).val(timeSelected);

						//replace honors
						replaceStr = "<option value=''> Both </option> ";
						for(var i = 0; i < courseInfoArr['isHonors'].length; i++)
						{
							if(courseInfoArr['isHonors'][i] == 1)
							{
								var honors_str = "Yes";
							} else {
								var honors_str = "No";
							}
							replaceStr += " <option value='"+courseInfoArr['isHonors'][i]+"'>"+honors_str+"</option> ";
						}
						$("#honors"+number).prop('disabled', false);
						$("#honors"+number).html(replaceStr);
						$("#honors"+number).val(honorsSelected);

						//replace credits
						replaceStr = "<option value=''> Select </option> ";
						for(var i = 0; i < courseInfoArr['credits'].length; i++)
						{
							replaceStr += " <option value='"+courseInfoArr['credits'][i]+"'>"+courseInfoArr['credits'][i]+"</option> ";
						}
						$("#credits"+number).prop('disabled', false);
						$("#credits"+number).html(replaceStr);
						$("#credits"+number).val(creditsSelected);
          } else {
            alert("Error in recieving data");
          }
        }
			});
		}

		function changeOnDays(number) {
			var valSelected = $("#days"+number).val();
			var courseSelected = $("#prefix"+number).val();
			var noSelected = $("#courseNo"+number).val();
			var crnSelected = $("#crn"+number).val();
			var timeSelected = $("#time"+number).val();
			var honorsSelected = $("#honors"+number).val();
			var creditsSelected = $("#credits"+number).val();
			if(timeSelected != "")
			{
				var ajax_data = {action: "days", prefix: courseSelected, courseNO: noSelected, days: valSelected, times: timeSelected};
			} else {
				var ajax_data = {action: "days", prefix: courseSelected, courseNO: noSelected, days: valSelected};
			}
			if(honorsSelected != "")
				ajax_data.isHonors = honorsSelected;
			if(creditsSelected != "")
				ajax_data.credits = creditsSelected;
			if(crnSelected == "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: ajax_data,
	        success: function(output) {
	          if(output != 0)
	          {
							var courseInfoArr = JSON.parse(output);

							//if a time hasn't been selected yet, change the dropdown
							//replace times
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['times'].length; i++)
							{
								if(courseInfoArr['times'][i] != "12:00 am - 12:00 am")
									replaceStr += " <option value='"+courseInfoArr['times'][i]+"'>"+courseInfoArr['times'][i]+"</option> ";
							}
							$("#time"+number).prop('disabled', false);
							$("#time"+number).html(replaceStr);
							$("#time"+number).val(timeSelected);

							//replace CRNs
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['crns'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['crns'][i]+"'>"+courseInfoArr['crns'][i]+"</option> ";
							}
							$("#crn"+number).prop('disabled', false);
							$("#crn"+number).html(replaceStr);

							//replace honors
							replaceStr = "<option value=''> Both </option> ";
							for(var i = 0; i < courseInfoArr['isHonors'].length; i++)
							{
								if(courseInfoArr['isHonors'][i] == 1)
								{
									var honors_str = "Yes";
								} else {
									var honors_str = "No";
								}
								replaceStr += " <option value='"+courseInfoArr['isHonors'][i]+"'>"+honors_str+"</option> ";
							}
							$("#honors"+number).prop('disabled', false);
							$("#honors"+number).html(replaceStr);
							$("#honors"+number).val(honorsSelected);

							//replace credits
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['credits'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['credits'][i]+"'>"+courseInfoArr['credits'][i]+"</option> ";
							}
							$("#credits"+number).prop('disabled', false);
							$("#credits"+number).html(replaceStr);
							$("#credits"+number).val(creditsSelected);
	          } else {
	            alert("Error in recieving data");
	          }
	        }
				});
			}
		}

		function changeOnTime(number) {
			var valSelected = $("#time"+number).val();
			var courseSelected = $("#prefix"+number).val();
			var noSelected = $("#courseNo"+number).val();
			var crnSelected = $("#crn"+number).val();
			var daySelected = $("#days"+number).val();
			var honorsSelected = $("#honors"+number).val();
			var creditsSelected = $("#credits"+number).val();
			if(daySelected != "")
			{
				var ajax_data = {action: "time", prefix: courseSelected, courseNO: noSelected, days: daySelected, time: valSelected};
			} else {
				var ajax_data = {action: "time", prefix: courseSelected, courseNO: noSelected, time: valSelected};
			}
			if(honorsSelected != "")
				ajax_data.isHonors = honorsSelected;
			if(creditsSelected != "")
				ajax_data.credits = creditsSelected;
			if(crnSelected == "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: ajax_data,
	        success: function(output) {
	          if(output != 0)
	          {
							var courseInfoArr = JSON.parse(output);

							//replace days
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['days'].length; i++)
							{
								if(courseInfoArr['days'][i] != "")
									replaceStr += " <option value='"+courseInfoArr['days'][i]+"'>"+courseInfoArr['days'][i]+"</option> ";
							}
							$("#days"+number).prop('disabled', false);
							$("#days"+number).html(replaceStr);
							$("#days"+number).val(daySelected);


							//replace CRNs
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['crns'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['crns'][i]+"'>"+courseInfoArr['crns'][i]+"</option> ";
							}
							$("#crn"+number).prop('disabled', false);
							$("#crn"+number).html(replaceStr);

							//replace honors
							replaceStr = "<option value=''> Both </option> ";
							for(var i = 0; i < courseInfoArr['isHonors'].length; i++)
							{
								if(courseInfoArr['isHonors'][i] == 1)
								{
									var honors_str = "Yes";
								} else {
									var honors_str = "No";
								}
								replaceStr += " <option value='"+courseInfoArr['isHonors'][i]+"'>"+honors_str+"</option> ";
							}
							$("#honors"+number).prop('disabled', false);
							$("#honors"+number).html(replaceStr);
							$("#honors"+number).val(honorsSelected);

							//replace credits
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['credits'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['credits'][i]+"'>"+courseInfoArr['credits'][i]+"</option> ";
							}
							$("#credits"+number).prop('disabled', false);
							$("#credits"+number).html(replaceStr);
							$("#credits"+number).val(creditsSelected);
	          } else {
	            alert("Error in recieving data");
	          }
	        }
				});
			}
		}

		function changeOnHonors(number) {
			var valSelected = $("#honors"+number).val();
			var courseSelected = $("#prefix"+number).val();
			var noSelected = $("#courseNo"+number).val();
			var crnSelected = $("#crn"+number).val();
			var daySelected = $("#days"+number).val();
			var timeSelected = $("#time"+number).val();
			var creditsSelected = $("#credits"+number).val();
			var ajax_data = {action: "honors", prefix: courseSelected, courseNO: noSelected, isHonors: valSelected};
			if(timeSelected != "")
				ajax_data.times = timeSelected;
			if(daySelected != "")
				ajax_data.days = daySelected;
			if(creditsSelected != "")
				ajax_data.credits = creditsSelected;
			if(crnSelected == "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: ajax_data,
	        success: function(output) {
	          if(output != 0)
	          {
							var courseInfoArr = JSON.parse(output);

							//replace days
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['days'].length; i++)
							{
								if(courseInfoArr['days'][i] != "")
									replaceStr += " <option value='"+courseInfoArr['days'][i]+"'>"+courseInfoArr['days'][i]+"</option> ";
							}
							$("#days"+number).prop('disabled', false);
							$("#days"+number).html(replaceStr);
							$("#days"+number).val(daySelected);

							//replace times
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['times'].length; i++)
							{
								if(courseInfoArr['times'][i] != "12:00 am - 12:00 am")
									replaceStr += " <option value='"+courseInfoArr['times'][i]+"'>"+courseInfoArr['times'][i]+"</option> ";
							}
							$("#time"+number).prop('disabled', false);
							$("#time"+number).html(replaceStr);
							$("#time"+number).val(timeSelected);

							//replace CRNs
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['crns'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['crns'][i]+"'>"+courseInfoArr['crns'][i]+"</option> ";
							}
							$("#crn"+number).prop('disabled', false);
							$("#crn"+number).html(replaceStr);
							$("#crn"+number).val(crnSelected);

							//replace credits
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['credits'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['credits'][i]+"'>"+courseInfoArr['credits'][i]+"</option> ";
							}
							$("#credits"+number).prop('disabled', false);
							$("#credits"+number).html(replaceStr);
							$("#credits"+number).val(creditsSelected);
	          } else {
	            alert("Error in recieving data");
	          }
	        }
				});
			}
		}

		function changeOnCredits(number) {
			var valSelected = $("#credits"+number).val();
			var courseSelected = $("#prefix"+number).val();
			var noSelected = $("#courseNo"+number).val();
			var crnSelected = $("#crn"+number).val();
			var daySelected = $("#days"+number).val();
			var timeSelected = $("#time"+number).val();
			var honorsSelected = $("#honors"+number).val();
			var ajax_data = {action: "credits", prefix: courseSelected, courseNO: noSelected, credits: valSelected};
			if(timeSelected != "")
				ajax_data.times = timeSelected;
			if(daySelected != "")
				ajax_data.days = daySelected;
			if(honorsSelected != "")
				ajax_data.isHonors = honorsSelected;
			if(crnSelected == "")
			{
				$.ajax({
	        method: "POST",
	        url: "course_select_funcs.php",
	        data: ajax_data,
	        success: function(output) {
	          if(output != 0)
	          {
							var courseInfoArr = JSON.parse(output);

							//replace days
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['days'].length; i++)
							{
								if(courseInfoArr['days'][i] != "")
									replaceStr += " <option value='"+courseInfoArr['days'][i]+"'>"+courseInfoArr['days'][i]+"</option> ";
							}
							$("#days"+number).prop('disabled', false);
							$("#days"+number).html(replaceStr);
							$("#days"+number).val(daySelected);

							//replace times
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['times'].length; i++)
							{
								if(courseInfoArr['times'][i] != "12:00 am - 12:00 am")
									replaceStr += " <option value='"+courseInfoArr['times'][i]+"'>"+courseInfoArr['times'][i]+"</option> ";
							}
							$("#time"+number).prop('disabled', false);
							$("#time"+number).html(replaceStr);
							$("#time"+number).val(timeSelected);

							//replace CRNs
							replaceStr = "<option value=''> Select </option> ";
							for(var i = 0; i < courseInfoArr['crns'].length; i++)
							{
								replaceStr += " <option value='"+courseInfoArr['crns'][i]+"'>"+courseInfoArr['crns'][i]+"</option> ";
							}
							$("#crn"+number).prop('disabled', false);
							$("#crn"+number).html(replaceStr);
							$("#crn"+number).val(crnSelected);

							//replace honors
							replaceStr = "<option value=''> Both </option> ";
							for(var i = 0; i < courseInfoArr['isHonors'].length; i++)
							{
								if(courseInfoArr['isHonors'][i] == 1)
								{
									var honors_str = "Yes";
								} else {
									var honors_str = "No";
								}
								replaceStr += " <option value='"+courseInfoArr['isHonors'][i]+"'>"+honors_str+"</option> ";
							}
							$("#honors"+number).prop('disabled', false);
							$("#honors"+number).html(replaceStr);
							$("#honors"+number).val(honorsSelected);
	          } else {
	            alert("Error in recieving data");
	          }
	        }
				});
			}
		}

		function addCourseLine()
		{
			courses++;
			var addStr = '<tr id="row'+courses+'">'+
				'<td>'+
					'<select id="prefix'+courses+'" onchange="changeCourseNo('+courses+');" name="prefix[]" >'+
						'<option value="">Select</option>'+
						'<?php foreach($coursePrefixes as $key=>$prefix) {?>'+
							'<option value="<?=$prefix?>"><?=$prefix?></option>'+
						'<?php } ?>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="courseNo'+courses+'" onchange="changeCRN('+courses+');" name ="courseNo[]" disabled>'+
						'<option value = "">Course No.</option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="honors'+courses+'" onchange="changeOnHonors('+courses+');" name="honors[]" disabled>'+
						'<option value = "">Both</option>'+
						'<option value= "1">Yes</option>'+
						'<option value= "0">No</option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="crn'+courses+'" onchange="changeOnCRN('+courses+');" name="crn[]" disabled>'+
						'<option>CRN</option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="days'+courses+'" onchange="changeOnDays('+courses+')" name="days[]" disabled>'+
						'<option>Day</option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="time'+courses+'" onchange="changeOnTime('+courses+')" name="time[]" disabled>'+
						'<option>Time</option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select id="credits'+courses+'" onchange="changeOnCredits('+courses+')" name="credits[]" disabled>'+
						'<option value="">Credits</option>'+
					'</select>'+
				'</td>'+
			'</tr>';

			$(addStr).insertAfter($("#row"+(courses-1)));
		}

		function removeCourseLine()
		{
			if(courses > 1)
			{
				$("#row"+courses).remove();
				courses--;
			}
		}
	</script>
</html>
