<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header mt-25" style="padding-left:20px">
        <a href="home.php" class="section-title text-black"><?php include('../dist/includes/title.php');?></a>
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