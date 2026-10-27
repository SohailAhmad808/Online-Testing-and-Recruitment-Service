<!-- Header -->
  <?php
  $Current_page= basename($_SERVER['PHP_SELF']);
  ?>
  <header class="border-bottom mb-3 sticky-top">
    <div class="container-fluid d-flex align-items-center gap-2 py-1 bg-white " style="color:#6D28D9 ">
      <!-- Logo -->
      <img src="../Picture/Sproject.png" 
          alt="Logo" 
          class="rounded-circle border border-primary me-2"
          style="height:50px; width:50px;">

      <!-- Title -->
      <div>
        <h5 class="mb-0 fw-bold">ONLINE TESTING AND RECRUITMENT SERVICE</h5>
        <small class="text-muted">Secure and Transparent Recruitment Platform</small>
      </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark" style="background-color:#6D28D9;">
      <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
          <span class="navbar-toggler-icon"></span> 
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="mainNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link <?php if($Current_page == 'index.php'){ echo "active"; } ?>" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link <?php if($Current_page == 'MAllProject.php'){ echo "active"; } ?>" href="MAllProject.php">Posts</a></li>
            <li class="nav-item"><a class="nav-link <?php if($Current_page == 'Announcement.php'){ echo "active"; } ?>" href="Announcement.php">Announcement</a></li>
            <li class="nav-item"><a class="nav-link <?php if($Current_page == 'Mtestlist.php'){ echo "active"; } ?>" href="Mtestlist.php">Test</a></li>
            <li class="nav-item"><a class="nav-link <?php if($Current_page == 'MResult.php'){ echo "active"; } ?>" href="MResult.php">Results&Roll_Number_Slip</a></li>
            <li class="nav-item"><a class="nav-link" href="../Login&Registration/SignUp.php" target="_blank">Register</a></li>
            <li class="nav-item"><a class="nav-link" href="../Login&Registration/UserLogin.php" target="_blank">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="../Login&Registration/AdminLogin.php" target="_blank">Admin</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>