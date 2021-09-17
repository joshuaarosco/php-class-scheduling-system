<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header" style="padding-left:20px">

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!--
          <li class="">
            <a href="home.php" class="" style="font-size:14px"><i class="glyphicon glyphicon-star-empty"></i> Class Schedule</a>
          </li>
          <li class="">
            <a href="exam.php" class="" style="font-size:14px"><i class="glyphicon glyphicon-list-alt"></i> Exam Schedule</a>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="glyphicon glyphicon-file"></i> Entry
            </a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu">
                  <li>
                    <a href="class.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Class
                    </a>
                  </li>

                  <li>
                    <a href="room.php">
                      <i class="glyphicon glyphicon-scale text-green"></i> Room
                    </a>
                  </li>

                  <li>
                    <a href="subject.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Subject
                    </a>
                  </li>

                  <li>
                    <a href="teacher.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Teacher
                    </a>
                  </li>
                  <li>
                    <a href="signatories.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Signatories
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="glyphicon glyphicon-wrench"></i> Maintenance
            </a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu">

                  <li>
                    <a href="department.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Department
                    </a>
                  </li>
                  <li>
                    <a href="designation.php">
                      <i class="glyphicon glyphicon-cutlery text-green"></i> Designation
                    </a>
                  </li>
                  <li>
                    <a href="program.php">
                      <i class="glyphicon glyphicon-cutlery text-green"></i> Program
                    </a>
                  </li>

                  <li>
                    <a href="rank.php">
                      <i class="glyphicon glyphicon-send text-green"></i> Rank
                    </a>
                  </li>

                  <li>
                    <a href="salut.php">
                      <i class="glyphicon glyphicon-user text-green"></i> Salutation
                    </a>
                  </li>

                  <li>
                    <a href="sy.php">
                      <i class="glyphicon glyphicon-user text-green"></i> School Year
                    </a>
                  </li>

                  <li>
                    <a href="time.php">
                      <i class="glyphicon glyphicon-calendar text-green"></i> Time
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          -->
          <li class="">
            <a href="profile.php" class="dropdown-toggle text-black">
              <?php echo $_SESSION['name'];?>
              <i class="ml-10 glyphicon glyphicon-user text-black"></i>
            </a>
          </li>
          <li class="mt-13">
            <span>|</span>
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
      </div><!-- /.navbar-custom-menu -->
    </div><!-- /.container-fluid -->
  </nav>
</header>