<?php session_start();
include("advising-mailer.php");
if(isset($_POST['action']) && $_POST['action'] != "")
{
  include("sensitive.php");

  switch($_POST['action'])
  {
    case "schedule":
      if(isset($_POST['array_str']) && $_POST['array_str'] != "" && isset($_SESSION['student']['eid']) && $_SESSION['student']['eid'] != "")
      {
        // Check connection
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = "SELECT *
                  FROM STUDENT
                  WHERE EID = '".$_SESSION['student']['eid']."'";

        $result = mysqli_query($conn, $query);
        if (mysql_num_rows($result)==0)
        {
          $query = "INSERT INTO STUDENT
                    VALUES ('".$_SESSION['student']['eid']."','".$_SESSION['student']['fname']."',
                    '".$_SESSION['student']['lname']."', '".$_SESSION['student']['emich']."')";
          mysqli_query($conn, $query);
        }

        $query = "INSERT INTO SCHEDULE
                  (scheduleDate, EID, advisorNetID)
                  VALUES ('".date('Y-m-d')."','".$_SESSION['student']['eid']."',
                  '".$_SESSION['user']['netID']."')";

        mysqli_query($conn, $query);
        $scheduleID = mysqli_insert_id($conn);
        if($scheduleID <= 0)
        {
          echo "Schedule did not get added into DB";
          break;
        } else {

          $schedule_arr = json_decode(stripslashes($_POST['array_str']), true);

          foreach($schedule_arr as $key=>$val)
          {
            $query = "INSERT INTO COURSE_ADVISED
                      (CRN, term, scheduleID)
                      VALUES ('".$val['CRN']."', '".$val['term']."', ".$scheduleID.")";
                      
            mysqli_query($conn, $query);
          }
          echo true;
		  
		  //email_schedule($_SESSION['student']['fname'], $_SESSION['student']['emich'], $_SESSION['student']['eid']);
		  
          break;
        }
      } else {
        echo "Array string not set.";
        break;
      }
      break;
  }

}
?>
