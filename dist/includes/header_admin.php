<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header mt-25" style="padding-left:20px">
        <a href="<?php echo $_SESSION['type']=='admin'?'home.php':'faculty_home.php';?>" class="section-title text-black"><?php include('../dist/includes/title.php');?></a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="">
            <a href="profile.php" class="dropdown-toggle text-black">
              <?php echo $_SESSION['name'];?>
              <i class="ml-10 glyphicon glyphicon-user text-black"></i>
            </a>
          </li>
          <li class="mt-13">
            <span>|</span>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggl text-blacke" data-toggle="dropdown">
              <i class="glyphicon glyphicon-wrench text-black"></i>
            </a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu">
                  <li>
                    <a href="department.php">
                      <i class="glyphicon glyphicon-briefcase text-black"></i> Department
                    </a>
                  </li>
                  <li>
                    <a href="designation.php">
                      <i class="glyphicon glyphicon-pushpin text-black"></i> Designation
                    </a>
                  </li>
                  <li>
                    <a href="program.php">
                      <i class="glyphicon glyphicon-flag text-black"></i> Program
                    </a>
                  </li>
                  <li>
                    <a href="rank.php">
                      <i class="glyphicon glyphicon-star text-black"></i> Rank
                    </a>
                  </li>
                  <li>
                    <a href="salut.php">
                      <i class="glyphicon glyphicon-certificate text-black"></i> Salutation
                    </a>
                  </li>
                  <li>
                    <a href="sy.php">
                      <i class="glyphicon glyphicon-calendar text-black"></i> School Year
                    </a>
                  </li>
                  <!-- <li>
                    <a href="time.php">
                      <i class="glyphicon glyphicon-calendar text-black"></i> Time
                    </a>
                  </li> -->
                </ul>
              </li>
            </ul>
          </li>
          <li class="">
            <a href="settings.php" style="color:#fff;" class="dropdown-toggle">
              <i class="glyphicon glyphicon-cog text-black"></i>
            </a>
          </li>
          <li class="">
            <a href="logout.php" class="dropdown-toggle">
              <i class="glyphicon glyphicon-off text-black"></i> 
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>