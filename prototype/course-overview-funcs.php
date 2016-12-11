<?php session_start();
if(isset($_POST['action']) && $_POST['action'] != "")
{
  include("sensitive.php");

  // Check connection
  if (mysqli_connect_errno()) {
      die("Connection failed: " . mysqli_connect_error());
  }

	switch($_POST['action']) {
		case "fetch_course_prefixes":
			//connection is named $conn
			$course_prefixes_sql = 'SELECT DISTINCT coursePrefix FROM COURSE';
			$result = mysqli_query($conn, $course_prefixes_sql);
			$rows_arr = array();
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$rows_arr[] = $row["coursePrefix"];
				}
				$results_json = json_encode($rows_arr);
	        		echo $results_json;
			}
			break;
		case "fetch_course_numbers":
			$contents_dictionary = array();

			$course_prefix = $_POST['course_prefix'];
			$course_numbers_sql = "SELECT DISTINCT courseNO FROM COURSE WHERE coursePrefix='" . $course_prefix . "'";
			$result = mysqli_query($conn, $course_numbers_sql);

			$rows_arr = array();
      if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                      $rows_arr[] = $row["courseNO"];
              }
							$contents_dictionary["course_numbers"] = $rows_arr;
							$results_json = json_encode($contents_dictionary);
							echo $results_json;
      }else{
				echo "no rows";
			}

			break;
		case "fetch_honors":
			$contents = array();

			$course_prefix = $_POST['course_prefix'];
			$course_number = $_POST['course_number'];
			$honors_sql = "SELECT DISTINCT isHonors FROM COURSE WHERE coursePrefix='" . $course_prefix . "'";

			/* to be cleaned up later
			if($course_number == ""){
				$honors_sql = "SELECT DISTINCT isHonors FROM COURSE WHERE coursePrefix='" . $course_prefix . "'";
			}else{
				$honors_sql = "SELECT DISTINCT isHonors FROM COURSE WHERE coursePrefix='" . $course_prefix . "' AND courseNO='" . $course_number . "'";
			}
			*/
			$result = mysqli_query($conn, $honors_sql);

			$rows_arr = array();
      if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                      $rows_arr[] = $row["isHonors"];
              }
							$contents_dictionary["honors"] = $rows_arr;
							$results_json = json_encode($contents_dictionary);
							echo $results_json;
      }else{
				echo "no rows";
			}

			break;

		case "fetch_advising_info":
			$course_alias_for_sql = "C";

			$course_prefix = $_POST['course_prefix'];
			$course_number = $_POST['course_number'];
			$honors = $_POST['is_honors'];

			$conditions_array = array();
			$conditions_array[] = $course_alias_for_sql . ".coursePrefix='" . $course_prefix . "'";
			if($course_number != ""){
				$conditions_array[] = $course_alias_for_sql . ".courseNO='" . $course_number . "'";
			}
			if($honors != ""){
				$conditions_array[] = $course_alias_for_sql . ".isHonors=" . $honors;
			}
			$conditions_string = "";
			if (count($conditions_array) < 0){
				echo "error; there cannot be a negative number of conditions in 'fetch advising info'";
				break;
			}else if (count($conditions_array) == 1) {
				$conditions_string = $conditions_array[0];
			}else {
				$conditions_string = implode(" AND ", $conditions_array);
			}

			//now I am done with building the conditions; I can do the sql select now
			$fetch_courses_sql =
			"SELECT C.coursePrefix, C.courseNO, C.CRN, C.isHonors, COUNT(CA.CRN) as ADVISED, C.actual, C.capacity FROM COURSE AS C LEFT OUTER JOIN COURSE_ADVISED AS CA ON C.CRN = CA.CRN WHERE " . $conditions_string . " GROUP BY C.CRN";

			$result = mysqli_query($conn, $fetch_courses_sql);

			$rows_arr = array();
      if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
										$cp = $row["coursePrefix"];
										$cn = $row["courseNO"];
										$crn = $row["CRN"];
										$honors = $row["isHonors"];
										$advised = $row["ADVISED"];
										$actual = $row["actual"];
										$capacity = $row["capacity"];
                    $rows_arr[] = array("coursePrefix" => $cp, "courseNO" => $cn, "CRN" => $crn, "isHonors" => $honors,
																				"ADVISED" => $advised, "actual" => $actual, "capacity" => $capacity);
              }
							$results_json = json_encode($rows_arr);
							echo $results_json;
      }else{
				echo "no rows";
			}

			break;
  }

}
?>
