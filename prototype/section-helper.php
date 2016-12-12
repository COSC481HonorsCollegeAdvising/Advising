<?php session_start();

include("sensitive.php");

if(isset($_POST['action']) && $_POST['action'] != ""){
	if($_POST['action'] == "fetch_section_details"){
		if(isset($_SESSION["CRN"]) && $_SESSION["CRN"] != ""){
			$crn = $_SESSION["CRN"];
			$fetch_course_sql =
			"SELECT C.coursePrefix, C.courseNO, C.CRN, C.isHonors, COUNT(CA.CRN) as ADVISED, C.actual, C.capacity FROM COURSE AS C LEFT OUTER JOIN COURSE_ADVISED AS CA ON C.CRN = CA.CRN WHERE C.CRN='" . $crn . "' GROUP BY C.CRN";

			$result = mysqli_query($conn, $fetch_course_sql);

			$row_to_return = array();
      if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
										$cp = $row["coursePrefix"];
										$cn = $row["courseNO"];
										$crn = $row["CRN"];
										$honors = $row["isHonors"];
										$advised = $row["ADVISED"];
										$actual = $row["actual"];
										$capacity = $row["capacity"];
                    $row_to_return += array("coursePrefix" => $cp, "courseNO" => $cn, "CRN" => $crn, "isHonors" => $honors,
																				"ADVISED" => $advised, "actual" => $actual, "capacity" => $capacity);
										break;//only get 1 result
							}
							$results_json = json_encode($row_to_return);
							echo $results_json;
      }else{
				echo "no rows";
			}
		}
	}else if($_POST['action'] == "fetch_students_advised_for_section"){
		$crn = $_SESSION["CRN"];
		$fetch_students_sql = "SELECT S.firstName, S.lastName, S.EID, A.firstName AS AdvisorFirstName, A.lastName AS AdvisorLastName, SH.scheduleDate " .
													"FROM STUDENT AS S, ADVISOR AS A, SCHEDULE AS SH, COURSE_ADVISED AS CA " .
													"WHERE CA.CRN='" . $crn . "' " .
													"AND SH.scheduleID=CA.scheduleID AND SH.advisorNetID=A.advisorNetID AND SH.EID=S.EID";
		//echo $fetch_students_sql;
		$result = mysqli_query($conn, $fetch_students_sql);
		$rows_arr = array();
		if($result->num_rows > 0){
						while($row = $result->fetch_assoc()){
									$firstName = $row["firstName"];
									$lastName = $row["lastName"];
									$EID = $row["EID"];
									$advisorFirstName = $row["AdvisorFirstName"];
									$advisorLastName = $row["AdvisorLastName"];
									$scheduleDate = $row["scheduleDate"];
									$rows_arr[] = array("firstName" => $firstName, "lastName" => $lastName, "EID" => $EID,
																			"AdvisorFirstName" => $advisorFirstName, "AdvisorLastName" => $advisorLastName, "scheduleDate" => $scheduleDate);
						}
						$results_json = json_encode($rows_arr);
						echo $results_json;
		}else{
			echo "no rows";
		}
	}
}

?>
