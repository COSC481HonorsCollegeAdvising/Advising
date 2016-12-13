<?php session_start();
//include("advising-mailer.php");
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

        $student_fname = mysqli_real_escape_string($conn, strip_tags($_SESSION['student']['fname']));
        $student_lname = mysqli_real_escape_string($conn, strip_tags($_SESSION['student']['lname']));

        if (mysqli_num_rows($result)==0)
        {
          $query = "INSERT INTO STUDENT
                    VALUES ('".$_SESSION['student']['eid']."','".$student_fname."',
                    '".$student_lname."', '".$_SESSION['student']['emich']."')";
          if(!$result = mysqli_query($conn, $query))
          {
            echo("Error description: " . mysqli_error($conn));
          }
        }

        $query = "INSERT INTO SCHEDULE
                  (scheduleDate, EID, advisorNetID)
                  VALUES ('".date('Y-m-d')."','".$_SESSION['student']['eid']."',
                  '".$_SESSION['user']['netID']."')";

        if(!$result = mysqli_query($conn, $query))
        {
          echo("Error description: " . mysqli_error($conn));
        }
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

            if(!$result = mysqli_query($conn, $query))
            {
              echo("Error description: " . mysqli_error($conn));
            }
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
