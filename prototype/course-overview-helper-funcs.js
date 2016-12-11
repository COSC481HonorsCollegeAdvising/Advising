function fetch_course_prefixes() {
	$.ajax({
		method: "POST",
		url: "course-overview-funcs.php",
		data: {action: "fetch_course_prefixes"},
		success: function(output) {
			try{
				var prefixes = JSON.parse(output);
				prefixes.sort();

				var options_string = "<option>-Select-</option>";
				for(let the_prefix of prefixes){
					options_string += "<option>" + the_prefix + "</option>";
				}

				var prefix_select = document.getElementById("course_prefix_select");
				prefix_select.innerHTML = options_string;
			}catch(e){
				alert("error:" . e);
			}
		}
	});
}

function fetch_and_set_course_numbers() {
	//if the user has not selected a course prefix, they are not allowed to select a course number
	var selected_course_prefix = document.getElementById("course_prefix_select").value;
	if (selected_course_prefix == "-Select-") {
		var course_select = document.getElementById("course_number_select");
		course_select.innerHTML = "<option>-Select-</option>";
		return;
	}

	$.ajax({
		method: "POST",
		url: "course-overview-funcs.php",
		data: {action: "fetch_course_numbers", course_prefix: selected_course_prefix},
		success: function(output) {

			try {
					var obj = JSON.parse(output);
					var course_numbers = obj["course_numbers"];
					var honors = obj["honors"];
					course_numbers.sort();
					var course_numbers_string = "<option>-Select-</option>";
					for (let num of course_numbers) {
						course_numbers_string += "<option>" + num + "</option>";
					}

					var course_select = document.getElementById("course_number_select");
					course_select.innerHTML = course_numbers_string;
			}catch(e){
				alert("error: " . e)
			}
		}
	});
}

function fetch_and_set_honors() {
	//if the user has not selected a course prefix, we do not let them select honors
	var selected_course_prefix = document.getElementById("course_prefix_select").value;
	if (selected_course_prefix == "-Select-") {
		var course_select = document.getElementById("honors_select");
		course_select.innerHTML = "<option>-Select-</option>";
		return;
	}

	var selected_course_number = document.getElementById("course_number_select").value;
	selected_course_number = selected_course_number == "-Select-" ? "" : selected_course_number;

	$.ajax({
		method: "POST",
		url: "course-overview-funcs.php",
		data: {action: "fetch_honors", course_prefix: selected_course_prefix, course_number: selected_course_number},
		success: function(output) {
			try {
					var obj = JSON.parse(output);
					var honors = obj["honors"];
					honors.sort();
					var honors_select_options = Array();
					if(honors.length == 1) {
						honors_select_options.push("-NO-");
					}else if (honors.length == 2) {
						honors_select_options.push("-Both-", "-YES-", "-NO-");
					}
					honors_string = "";
					for (let val of honors_select_options) {
						honors_string += "<option>" + val + "</option>";
					}

					var course_select = document.getElementById("honors_select");
					course_select.innerHTML = honors_string;
			}catch(e){
				alert("error: " . e)
			}
		}
	});
}

function fetch_both_honors_and_course_numbers(){
	fetch_and_set_honors();
	fetch_and_set_course_numbers();
}

function fetch_and_fill_table(){
	var coursePrefix = document.getElementById("course_prefix_select").value;
	if(coursePrefix == "-Select-"){
		alert("please at least select a course prefix");
		return;
	}
	var courseNumber = document.getElementById("course_number_select").value;
	courseNumber = courseNumber == "-Select-" ? "" : courseNumber;
	var honors = document.getElementById("honors_select").value;

	if(honors == "-Select-" || honors == "-Both-"){
		honors = "";
	}else {
		honors = honors == "-NO-" ? "0" : "1";
	}

	$.ajax({
		method: "POST",
		url: "course-overview-funcs.php",
		data: {action: "fetch_advising_info", course_prefix: coursePrefix, course_number: courseNumber, is_honors: honors},
		success: function(output) {
			var records = JSON.parse(output);

			var results_table_string = generate_table_header();
			results_table_string += generate_entire_table(records);

			var results_table = document.getElementById("course_info_table");
			results_table.innerHTML = results_table_string;
		}
	});
}

function generate_table_header(){
	return "<tr><th>Prefix</th>\
		<th>Course No.</th>\
		<th>CRN</th>\
		<th>Honors</th>\
		<th>Advised</th>\
		<th>Registered</th>\
		<th>Capacity</th>\
		<th>Action</th></tr>";
}

function generate_table_row(map, row_number){
	var row_contents = "<tr>" + "<td>" + map["coursePrefix"] + "</td>";
	row_contents += "<td>" + map["courseNO"] + "</td>";
	row_contents += "<td id=\"CRN" + String(row_number) + "\">" + map["CRN"] + "</td>";
	var is_honors = String(map["isHonors"]) == "0" ? "NO" : "YES";
	row_contents +=	"<td>" + is_honors + "</td>";
	row_contents +=	"<td>" + map["ADVISED"] + "</td>";
	row_contents += "<td>" + map["actual"] + "</td>";
	row_contents += "<td>" + map["capacity"] + "</td>";
	row_contents +="<td><button id=\"button@" + String(row_number) + "\" onclick=\"segue_to_detail_page(this.id)\">Details</button></td>";
	row_contents += "</tr>";

	return row_contents;
}

function generate_entire_table(records){
	var row_number = 0;
	var table_string = "";
	for(var i in records){
		var res = generate_table_row(records[i], row_number);
		table_string += res;
		row_number += 1;
	}
	return table_string;
}

function segue_to_detail_page(button_id) {
	var row_num = button_id.split("@")[1];
	var crn = document.getElementById("CRN"+row_num).innerHTML;

	$.ajax({
		method: "POST",
		url: "set-course-overview-crn-session_variable.php",
		data: {action: "set-crn", crn_data: crn},
		success: function(output) {
			window.location.href = 'section-details.php';
		}
	});
}
