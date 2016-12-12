/*
	This function fetches the section details for a course.
	It assumes that the crn session variable is set to something valid.
	It will make an ajax call and fetch an map of
	Prefix	Course No.	CRN	Honors	Advised	Registered	Capacity
*/

function fetch_section_details() {
	$.ajax({
		method: "POST",
		url: "section-helper.php",
		data: {action: "fetch_section_details"},
		success: function(output) {
			try{
				var map = JSON.parse(output);
				var header_text = generetate_section_detail_header();
				var row_text = generate_section_detail_row(map);
				var header_and_row = header_text + row_text;

				var section_detail_table = document.getElementById("section_detail_table");
				section_detail_table.innerHTML = header_and_row;
			}catch(e){
				alert("error in fetch of section detail");
			}

		}
	});
}

function generetate_section_detail_header() {
	var to_return = "<tr>\
	<th>Prefix</th>\
	<th>Course No.</th>\
	<th>CRN</th>\
	<th>Honors</th>\
	<th>Advised</th>\
	<th>Registered</th>\
	<th>Capacity</th>\
	</tr>";
	return to_return;
}

function generate_section_detail_row(map){
	var row_contents = "<tr>" + "<td>" + map["coursePrefix"] + "</td>";
	row_contents += "<td>" + map["courseNO"] + "</td>";
	row_contents += "<td>" + map["CRN"] + "</td>";
	var is_honors = String(map["isHonors"]) == "0" ? "NO" : "YES";
	row_contents +=	"<td>" + is_honors + "</td>";
	row_contents +=	"<td>" + map["ADVISED"] + "</td>";
	row_contents += "<td>" + map["actual"] + "</td>";
	row_contents += "<td>" + map["capacity"] + "</td>";
	row_contents += "</tr>";

	return row_contents;
}

/*
	The following functions are for fetching the students that are registered
	for the course
*/
function fetch_students_advised_for_section(){
	$.ajax({
		method: "POST",
		url: "section-helper.php",
		data: {action: "fetch_students_advised_for_section"},
		success: function(output) {
			try{
				var students_advised_table = document.getElementById("students_advised_table");
				var table_text = "";
				if(output == "no rows"){
					table_text += generate_all_students_advised_rows(Array());
				}else{
					var records = JSON.parse(output);
					table_text += generate_all_students_advised_rows(records);
				}
				students_advised_table.innerHTML = table_text;
			}catch(e){
				alert("error in fetch of section detail");
			}

		}
	});
}

function generate_students_advised_header(){
	var header = "<tr>\
		<th>First Name</th>\
		<th>Last Name</th>\
		<th>EID</th>\
		<th>Advisor</th>\
		<th>Date</th>\
	</tr>";
	return header;
}

function generate_students_advised_row(map){
	var row_contents = "<tr>\
		<th>" + map["firstName"] + "</th>\
		<th>" + map["lastName"] + "</th>\
		<th>" + map["EID"] + "</th>\
		<th>" + map["AdvisorFirstName"] + " " + map["AdvisorLastName"] + "</th>\
		<th>" + map["scheduleDate"] + "</th>\
	</tr>";
	return row_contents;
}

function generate_all_students_advised_rows(records){
	table_string = generate_students_advised_header();
	if(records.length > 0) {
		for(var i in records){
			table_string += generate_students_advised_row(records[i]);
		}
	}else{
		table_string += "<tr><th> No students in section</th></tr>";
	}
	return table_string;
}
