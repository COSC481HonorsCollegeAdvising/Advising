<?php
  session_start();

  if(!isset($_SESSION['user']['netID']) || $_SESSION['user']['netID'] == "")
  {
  	header("Location: login.php");
  	die();
  }
  if(!isset($_SESSION['user']['isAdmin']) || $_SESSION['user']['isAdmin'] != 1)
  {
  	header("Location: home.php");
  	die();
  }

  include("sensitive.php");

  // Check connection
  if (mysqli_connect_errno()) {
      die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT *
          FROM ADVISOR";

  $result = mysqli_query($conn, $query);

  while($row=mysqli_fetch_assoc($result))
  {
    $advisors[] = $row;
  }
  include("header.php");
 ?>

  <script>
  function validateEmail()  {
    var email = document.getElementsByName('emich')[0].value;
    if (/^\w{1,8}@emich\.edu$/.test(email)) {
      email = /^\w{1,8}/.exec(email);
    }

    if (/^\w{1,8}$/.test(email))  {
      return true;
    }
    alert("Please enter a valid netID or emich email.");
    return false;
  }

  </script>
    <div class="container">
      <div style="text-align: center; padding-bottom: 10px;">
        <span><b>Add Advisor</b></span>
      </div>
      <div class="row">
        <div class="small-4 columns centered">
          <label>
            First Name:
            <input id="fname" type="text"/>
          </label>
        </div>
        <div class="small-4 columns centered">
          <label>
            Last Name:
            <input id="lname" type="text"/>
          </label>
        </div>
        <div class="small-4 columns centered">
          <label>
            NetID:
            <input id="emich" type="text"/>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="small-6 columns centered">
          <label>
            Temporary Password:
            <input id="password" type="password"/>
          </label>
        </div>
        <div class="small-6 columns centered">
          <label>
            Confirm Password:
            <input id="password2" type="password"/>
          </label>
        </div>
      </div>

      <div style="text-align: center; padding-top: 5px;">
        <button onclick="addAdvisor();">Add</button>
      </div>
    </div>
    <br/>
    <div class="container" style="display: inline-block">
      <div style="text-align: center; padding-bottom: 10px;">
        <span><b>Current Advisors</b></span>
      </div>
      <table class="add">
        <tr>
          <th>First</th>
          <th>Last</th>
          <th>Email</th>
          <th>Admin</th>
          <th colspan="2">Actions</th>
        </tr>
        <?php foreach($advisors as $key=>$person)
        { ?>
        <tr>
          <td><?=$person['firstName']?></td>
          <td><?=$person['lastName']?></td>
          <td><?=$person['advisorNetID']?>@emich.edu</td>
          <td><?php if ($person['isAdmin'] == 1) echo "Yes"; else echo "No";?></td>
          <td class="tableButton"><button onclick="toggleAdmin(<?=$person['isAdmin']?>, '<?=$person['advisorNetID']?>');"><?php if ($person['isAdmin'] == 1) echo "Demote from Admin"; else echo "Promote to Admin";?></button></td>
          <td class="tableButton"><button onclick="removeAdvisor('<?=$person['advisorNetID']?>')">Remove Access</button></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div style="text-align: center; padding-top: 10px;">
      <button onclick="window.location.href='home.php'">Home</button>
    </div>
  </body>
  <script>
    function toggleAdmin(adminStatus, netID)
    {
      if(adminStatus == 1)
      {
        var admin = 0;
      } else {
        var admin = 1;
      }
      $.ajax({ url: 'admin_functions.php',
         data: {action: 'status', admin: admin, netID: netID},
         type: 'post',
         success: function(output) {
                      window.location.reload();
                  }
      });
    }

    function addAdvisor()
    {
      var firstName = $("#fname").val().trim();
      var lastName = $("#lname").val().trim();
      var netID = $("#emich").val().trim();
      var password = $("#password").val().trim();
      var password2 = $("#password2").val().trim();

      if(password == password2)
      {
        $.ajax({ url: 'admin_functions.php',
           data: {action: 'addAdvisor', fname: firstName, lname: lastName, netID: netID, password: password},
           type: 'post',
           success: function(output) {
             if(output != 1){
              alert(output);
            } else {
                window.location.reload();
            }
          }
        });
      } else {
        alert("The passwords did not match.");
      }
    }

    function removeAdvisor(netID)
    {
      $.ajax({ url: 'admin_functions.php',
        data: {action: 'removeAdvisor', netID: netID},
        type: 'post',
        success: function(output) {
            window.location.reload();
        }
      });
    }

  </script>
</html>
