<?php

session_start();

//check for login
if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
{
	header("Location: login.php");
	die();
}

if(!isset($_SESSION['student']) || $_SESSION['student']['eid'] == "")
{
  header("Location: student-info.php");
  die();
}

include("sensitive.php");

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = array();
for($i=0; $i<count($_POST['prefix']); $i++)
{
  $q_temp = "SELECT *
          FROM COURSE
          WHERE ";
  $query_array = array();

  if($_POST['prefix'][$i] != "")
  {
    $query_array[] = "coursePrefix='".$_POST['prefix'][$i]."'";
  }
  if($_POST['courseNo'][$i] != "")
  {
    $query_array[] = "courseNO='".$_POST['courseNo'][$i]."'";
  }
  if($_POST['honors'][$i] != "")
  {
    $query_array[] = "isHonors=".$_POST['honors'][$i];
  }
  if($_POST['crn'][$i] != "")
  {
    $query_array[] = "CRN='".$_POST['crn'][$i]."'";
  }
  if($_POST['days'][$i] != "")
  {
    $query_array[] = "days='".$_POST['days'][$i]."'";
  }
  if($_POST['time'][$i] != "")
  {
    $time_arr = explode(" - ", $_POST['time'][$i]);
    $query_array[] = "timeStart='".$time_arr[0]."'";
    $query_array[] = "timeEnd='".$time_arr[1]."'";
  }
  if($_POST['credits'][$i] != "")
  {
    $query_array[] = "credits=".$_POST['credits'][$i];
  }

  $conditions = implode(" AND ", $query_array);
  $q_temp = $q_temp.$conditions;
  $query[] = $q_temp;
}

$classes = array();
$courseCount = 0;
foreach($query as $key=>$sql)
{
  $result = mysqli_query($conn, $sql);
  $courses = array();
  while($row = mysqli_fetch_assoc($result))
  {
    $courses[] = $row;
  }
  $classes[] = $courses;
}

$schedules2 = array();

function decideFit($courseArr, $stackArr) {
  foreach($stackArr as $key=>$stackCourse) {
    $courseDays = str_split($courseArr["days"]);
    $stackDays = str_split($stackCourse["days"]);
    if(!empty(array_intersect($stackDays, $courseDays)) && $stackCourse['days'] != "")
    {
      if(strtotime($courseArr["timeStart"]) >= strtotime($stackCourse["timeStart"]) && strtotime($courseArr["timeStart"]) <= strtotime($stackCourse["timeEnd"]))
      {
        return false;
      }
      if(strtotime($courseArr["timeEnd"]) >= strtotime($stackCourse["timeStart"]) && strtotime($courseArr["timeEnd"]) <= strtotime($stackCourse["timeEnd"]))
      {
        return false;
      }
      if(strtotime($courseArr["timeStart"]) <= strtotime($stackCourse["timeStart"]) && strtotime($courseArr["timeEnd"]) >= strtotime($stackCourse["timeEnd"]))
      {
        return false;
      }
    }
  }
  return true;
}

function makeSchedule($length, $tempArray = array())
{
  //base case
  if($length == count($GLOBALS['classes'])-1)
  {
    for($j = 0; $j < count($GLOBALS['classes'][$length]); $j++)
    {
      if($GLOBALS['courseCount'] < 500)
      {
        $fit_bool = decideFit($GLOBALS['classes'][$length][$j], $tempArray);
        if($fit_bool)
        {
            $tempArray[] = $GLOBALS['classes'][$length][$j];
            $GLOBALS['schedules2'][] = $tempArray;
            array_pop($tempArray);
            $GLOBALS['courseCount']++;
        }
      } else {
        break;
      }
    }
  //recursive case
  } else {
    for($i = 0; $i < count($GLOBALS['classes'][$length]); $i++)
    {
      if($GLOBALS['courseCount'] < 500)
      {
        $fit_bool = decideFit($GLOBALS['classes'][$length][$i], $tempArray);
        if($fit_bool)
        {
            $tempArray[] = $GLOBALS['classes'][$length][$i];
            makeSchedule($length+1, $tempArray);
            array_pop($tempArray);
        }
      } else {
        break;
      }
    }
  }
}

//call the recursive function to schedule
makeSchedule(0);

if(count($schedules2) == 0)
{ ?>
<script>
  alert("There were no schedules possible. Please revise your search terms.");
  window.location="select-courses.php";
</script>
<?php }
include("header.php");
?>
  <link rel='stylesheet' href='./JS/fullcalendar/fullcalendar.css' />
  <script src='./JS/fullcalendar/lib/moment.min.js'></script>
  <script src='./JS/fullcalendar/fullcalendar.js'></script>
  <style>
    #calendar {
      width: 90%;
      margin: 0 auto;
      background-color: #fff;
    }
    .fc-today {
      background-color: none !important;
    }

    .fc-event {
      background-color: #0b4d03;
      border: 1px solid #073901;
    }
    .page {
      background-color: #fff;
      width: 60%;
    }
  </style>
    <div class="page">
      <span><b>Student Schedule for <?=$_SESSION['student']['fname']?></b></span>
      <div id='calendar'></div>
      <div style="width: 100%; margin: auto; padding-top: 20px;" class="row">
        <div class="large-2 columns">
          <button style="float: left;" onclick="byFive(-1);"><<</button>
        </div>
        <div class="large-2 columns">
          <button style="float: left;" onclick="byOne(-1)"><</button>
        </div>
        <div class="large-4 columns">
          <span style="float: center;" id="total">Schedule 1 of <?=count($schedules2)?></span>
        </div>
        <div class="large-2 columns">
          <button style="float: right;" onclick="byOne(1);">></button>
        </div>
        <div class="large-2 columns">
          <button style="float: right;" onclick="byFive(1)">>></button>
        </div>
      </div>
    </div>
    <div>
      <div style="margin-top: 10px;">
        <button onclick="window.location.href='select-courses.php'">Go Back</button>
        <button onclick="submit(course);">Choose Schedule and Submit</button>
      </div>
    </div>
  </body>
  <script>
    var course = 0;
    var classArr = <?=json_encode($schedules2)?>;
    $(document).ready(function() {

      //render the calendar, using the week 12/6/2016
      $('#calendar').fullCalendar({
          // put your options and callbacks here
          header: {
              left: '',
              center: '',
              right: ''
          },
          height: 450,
          defaultView: 'agendaWeek',
          scrollTime: '07:30:00',
          slotDuration: '00:30:00',
          allDaySlot: false,
          minTime:"07:00:00",
          maxTime:"22:00:00"
      });
			
			$("#calendar").fullCalendar("gotoDate", moment("2016-12-06", "YYYY-MM-DD"));
			$(".fc-day-header.fc-mon").html("Mon");
      $(".fc-day-header.fc-tue").html("Tue");
      $(".fc-day-header.fc-wed").html("Wed");
      $(".fc-day-header.fc-thu").html("Thu");
      $(".fc-day-header.fc-fri").html("Fri");
      $(".fc-day-header.fc-sat").html("Sat");
      $(".fc-day-header.fc-sun").html("Sun");
      if(classArr.length > 0)
        selectCourses(0);

    });

    function submit(courseIndex)
    {
      var scheduleChosen = classArr[courseIndex];
      //call AJAX to submit. if it works, take to confirmation. else, stay here and alert error.
      var jsonString = JSON.stringify(scheduleChosen);
      $.ajax({
        method: "POST",
        url: "schedule_funcs.php",
        data: {action: "schedule", array_str: jsonString, class_arr: scheduleChosen},
        success: function(output) {
          //console.log(output);
          if(output == 1)
          {
            window.location.href="complete.php";
          } else {
            echo(output);
          }
        }
      });
      window.location.href='complete.php'
    }

    function byOne(sign)
    {
      if((sign>0 && course + 1 < classArr.length) || (sign < 0 && course - 1 >= 0))
      {
        course = course + sign;
        selectCourses(course);
      }
    }
    function byFive(sign)
    {
      if((sign>0 && course + 5 < classArr.length) || (sign < 0 && course - 5 >= 0))
      {
        course = course + 5*sign;
        selectCourses(course);
      }
    }
    function decideDays(dayString)
    {
      var dayArray = dayString.split('');
      var dateReturn = [];
      for(var i = 0; i < dayArray.length; i++)
      {
        var date = ""
        if(dayArray[i] == "M") {
          date = '2016-12-05';
        } else if(dayArray[i] == "T") {
          date = '2016-12-06';
        } else if(dayArray[i] == "W") {
          date = '2016-12-07';
        } else if(dayArray[i] == "R") {
          date = '2016-12-08';
        } else if(dayArray[i] == "F") {
          date = '2016-12-09';
        } else if(dayArray[i] == "S") {
          date = '2016-12-10';
        } else {
          date = '2016-12-04';
        }
        dateReturn.push(date)
      }
      return dateReturn;
    }
    function selectCourses(index)
    {
      if(index < classArr.length && index >= 0)
      {
        $("#calendar").fullCalendar('removeEvents');
        /*var replaceStr = "<tr>"+
          "<th>Prefix</th>"+
          "<th>Course No.</th>"+
          "<th>Honors</th>"+
          "<th>CRN</th>"+
          "<th>Days</th>"+
          "<th>Start</th>"+
          "<th>End</th>"+
          "<th>Credits</th>"+
        "</tr>";*/
        var creditSum = 0;
        for(i=0; i<classArr[index].length; i++)
        {
          var class_str = classArr[index][i]['coursePrefix']+" "+classArr[index][i]['courseNO'];
          var start = moment(classArr[index][i]['timeStart'], "hh:mm a").format('HH:mm:ss');
          var end = moment(classArr[index][i]['timeEnd'], "hh:mm a").format('HH:mm:ss');
          var honorsStr = "";

          if(classArr[index][i]['isHonors'] == 1)
          {
            class_str +="H"
            honorsStr = "Yes";
          } else {
            honorsStr = "No";
          }
          var dayArr = decideDays(classArr[index][i]['days']);
          for(var j = 0; j < dayArr.length; j++)
          {
            var event = {title:class_str, start: dayArr[j]+'T'+start, end: dayArr[j]+'T'+end, id:index};
            $("#calendar").fullCalendar('renderEvent', event);
						console.log(event);
          }
          /*  replaceStr += "<tr>"+
            "<td>"+classArr[index][i]['coursePrefix']+"</td>"+
            "<td>"+classArr[index][i]['courseNO']+"</td>"+
            "<td>"+honorsStr+"</td>"+
            "<td>"+classArr[index][i]['CRN']+"</td>"+
            "<td>"+classArr[index][i]['days']+"</td>"+
            "<td>"+classArr[index][i]['timeStart']+"</td>"+
            "<td>"+classArr[index][i]['timeEnd']+"</td>"+
            "<td>"+classArr[index][i]['credits']+"</td>"+
          "</tr>";*/

          creditSum += Number(classArr[index][i]['credits']);
        }

        /*replaceStr += "<tr>"+
          "<td colspan='7' style='text-align: right; border: none;'>Total Credits:</td>"+
          "<td style='text-align: left; border: none;'>"+creditSum+"</td>"+
        "</tr>";

        $("#classList").html(replaceStr);*/
        $("#total").html("Schedule "+(course+1)+" of <?=count($schedules2)?>")
      }
    }
  </script>
</html>
