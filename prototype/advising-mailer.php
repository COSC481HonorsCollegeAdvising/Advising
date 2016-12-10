<?php
  session_start();
  require_once('Mail.php');
  require_once('Mail/mime.php');
  
  
  function email_schedule($student_name, $student_email, $student_eid) {
	  
		$sqlID = "SELECT scheduleID
					FROM SCHEDULE
					WHERE EID = '".$student_eid."'";
		$scheduleIdNum = mysqli_query($conn, $sqlID));
		  
		$sqlCRNs = "SELECT CRN
					FROM COURSE_ADVISED
					WHERE scheduleID = '".$scheduleIdNum."'";
		$result = mysqli_query($conn, $sqlCRNs);
		$crnList = mysqli_fetch_array($result);
		$scheduleString = "<table>";
		for($i=0;$i<count($crnList);$i++) {
			$sqlCourseInfo = "SELECT *
						  FROM COURSE
						  WHERE CRN = '".crnList[$i]."'";
			$resultCourse = mysqli_query($conn, $sqlCourseInfo);
			$classArr[$i] = mysqli_fetch_assoc($resultCourse);
			$scheduleString = $scheduleString."<tr><td>".$courseArr['coursePrefix']."</td>
						  <td>".$courseArr['courseNO']."</td>
						  <td>".$courseArr['isHonors']."</td>
						  <td>".$courseArr['CRN']."</td>
						  <td>".$courseArr['days']."</td>
						  <td>".$courseArr['timeStart']."</td>
						  <td>".$courseArr['timeEnd']."</td>
						  <td>".$courseArr['credits']."</td></tr>";
						  
		}
		
		$scheduleString = $scheduleString."</table>";
	    
		$message = "<p>Dear ".$student_name.",</p>
					<p>The following is the list of courses you selected today at your honors 
					advising appointment:
					<br/>".$scheduleString."</p>
					<p>If you have any questions, please contact your advisor.</p>";
		$target_email = $student_email;
		$from_email = "cspaldi1@emich.edu";
		$subject = "Advised Schedule Details";
		mail_any($target_email,$message,$from_email,$subject);
	}
  
  function mail_any($target_email, $message, $from_email, $subject) {

        $headers = array(
            'From' => $from_email,
            'To' => $target_email,
			//changed to my email for testing purposes currently
            'Reply-To' => $from_email,
            // 'Bcc' => $GLOBALS['fromEmail'],
			'Subject' => $subject
        );

        $to = $target_email . ',' . $from_email;
        // $crlf = array('ell' => "\r\n");
        //$crlf = array('eol' => "\r\n");
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setTXTBody();
        $mime->setHTMLBody($message);

        $config = array(
            'host' => 'smtp.emich.edu',
            'port' => 25,
        );

        $smtp = Mail::factory('smtp', $config);

        $mail = $smtp->send($to, $mime->headers($headers), $mime->get());

        if (PEAR::isError($mail)) {
            echo "Email didn't send, also it looks like this:" . $mail->getMessage();
        }
  }
 ?>