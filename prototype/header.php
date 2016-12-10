<html>
<head>
  <title>EMU Honors Advising</title>
  <script src="../foundation-6/js/vendor/jquery.js"></script>
  <script src="../foundation-6/js/vendor/foundation.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.css">
  <link rel="stylesheet" type="text/css" href="./CSS/foundation.min.css">
  <link rel="stylesheet" type="text/css" href="./CSS/global.css">
</head>
  <body>
    <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a href="home.php">Honors Advising Portal</a></h1>
        </li>
         <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>

      <section class="top-bar-section">
        <!-- Left Nav Section -->
        <?php if(isset($_SESSION['user']['netID']) && $_SESSION['user']['netID'] != "") { ?>
        <ul class="left">
          <li class="header-menu-item"><a href="student-info.php">Advising</a></li>
          <li class="header-menu-item"><a href="course-overview.php">Course Info</a></li>
          <?php if($_SESSION['user']['isAdmin'] == 1)
          { ?>
          <li class="header-menu-item"><a href="users.php">Users</a></li>
          <?php } ?>
          <!--<li class="has-dropdown">
            <a href="#">Right Button Dropdown</a>
            <ul class="dropdown">
              <li><a href="#">First link in dropdown</a></li>
              <li class="active"><a href="#">Active link in dropdown</a></li>
            </ul>
          </li> -->
        </ul>

        <!-- Right Nav Section -->
        <ul class="right">
          <li class="header-menu-item"><a href="account_details.php">Account</a></li>
        </ul>
        <?php } ?>
      </section>
    </nav>
