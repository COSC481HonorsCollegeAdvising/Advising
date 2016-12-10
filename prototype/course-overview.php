<html>
<?php
	include("sensitive.php");
	include("header.php");
?>
    <h1>Course Overview</h1>
    <div>
      <table>
        <tr>
          <th>Prefix</th>
          <th>Course No.</th>
					<th>Advisor</th>
          <th>Honors</th>
          <th>CRN</th>
        </tr>
        <tr>
          <td>
            <select id="course_prefix_select" onchange="fetch_course_numbers()">
              <option>ACC</option>
            </select>
          </td>
          <td>
            <select id="course_number_select">
              <option>-Select-</option>
            </select>
          </td>
					<td>
            <select id="advisor_select">
              <option>-Select-</option>
            </select>
          </td>
          <td>
            <select>
              <option>-Both-</option>
            </select>
          </td>
          <td>
            <select>
              <option>-Select-</option>
            </select>
          </td>
        </tr>
      </table>
      <div class="page">
        <span><b>Selected Courses</b></span>
        <table style="margin-top: 20px;">
          <tr>
            <th>Prefix</th>
            <th>Course No.</th>
            <th>CRN</th>
            <th>Honors</th>
            <th>Advised</th>
            <th>Registered</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
          <tr>
            <td>COSC</td>
            <td>111</td>
            <td>18792</td>
            <td>Yes</td>
            <td>4</td>
            <td>0</td>
            <td>20</td>
            <td><button onclick="window.location.href='section-details.php'">Details</button></td>
          </tr>
          <tr>
            <td>COSC</td>
            <td>111</td>
            <td>17293</td>
            <td>No</td>
            <td>2</td>
            <td>0</td>
            <td>35</td>
            <td><button>Details</button></td>
          </tr>
          <tr>
            <td>COSC</td>
            <td>111</td>
            <td>10293</td>
            <td>No</td>
            <td>3</td>
            <td>0</td>
            <td>35</td>
            <td><button>Details</button></td>
          </tr>
          <tr>
            <td>COSC</td>
            <td>111</td>
            <td>10293</td>
            <td>No</td>
            <td>1</td>
            <td>0</td>
            <td>35</td>
            <td><button>Details</button></td>
          </tr>
        </table>
      </div>
      <div style="margin-top: 10px;">
        <button onclick="window.location.href='home.php'">Home</button>
      </div>
    </div>

    <script>
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

	function fetch_course_numbers() {
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
						var course_numbers = JSON.parse(output);
						course_numbers.sort();
						course_numbers_string = "<option>-Select-</option>";
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

	fetch_course_prefixes();
    </script>

  </body>
</html>
