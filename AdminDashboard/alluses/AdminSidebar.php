<div class="m-md-2">
              <button class="hamburger-btn d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <span><i class="fa fa-bars" style="color:white; background-color:#6D28D9;"></i></span>
              </button>
            </div>
      <!--
      Convas Start Here
      -->
    <?php
      $Current_page= basename($_SERVER['PHP_SELF']);
    ?>
    <div class="offcanvas offcanvas-start" 
      data-bs-scroll="true" 
      tabindex="-1" 
      id="sidebarOffcanvas" 
      aria-labelledby="offcanvasWithBackdropLabel"
      style="--bs-offcanvas-width: 250px;">

      
      <div class="offcanvas-body p-0">
        
      <nav class="nav flex-column p-2">
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'AdminDashboard.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../Dashboard/AdminDashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserManagement.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../usermanagement/UserManagement.php"><i class="fa-solid fa-user-group me-2"></i> User Management</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'ManagePost.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../managepost/ManagePost.php"><i class="fa-solid fa-folder-open me-2"></i>Manage Post</a> 
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'ManageApplication.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../applications/ManageApplication.php"><i class="fa-solid fa-file-signature me-2"></i>Manage Applications</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'CreateTestdate.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold' ?>" href="../physicaltestcall/CreateTestdate.php"><i class="fa-solid fa-file-signature me-2"></i>ManagePhysicalTest</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'QuestionPool.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../questionpool/QuestionPool.php"><i class="fa-solid fa-circle-question me-2"></i>Question Pool</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'ManageTest.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../managetest/ManageTest.php"><i class="fa-solid fa-pen-to-square"></i> Manage Test</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'ManageResult.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../Testresult/ManageResult.php"><i class="fa-solid fa-chart-bar"></i> Manage Results</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'Online_Test_call.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold' ?>" href="../Onlinetestcall/Online_Test_call.php"><i class="fa-solid fa-computer"></i> Onlin test call</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'Announcement.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../announcement/Announcement.php"><i class="fa-solid fa-gear me-2"></i> Announcement</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'Setting.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../Mysetting/Setting.php"><i class="fa-solid fa-bullhorn me-2"></i> Setting</a>
        <a class="nav-link text-dark rounded my-1" href="../alluses/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </nav>
      </div>
    </div>

    <!--
    Aside
    -->
    <div class="row">
      <aside class="d-none d-md-block col-md-3 col-lg-2 mx-0"
      style="position: sticky; top: 0; height: 100vh; overflow-y: auto;">
      <nav class="nav flex-column p-1 mx-0">
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'AdminDashboard.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../Dashboard/AdminDashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'UserManagement.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../usermanagement/UserManagement.php"><i class="fa-solid fa-user-group me-2"></i> User Management</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'ManagePost.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../managepost/ManagePost.php"><i class="fa-solid fa-folder-open me-2"></i>Manage Post</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'ManageApplication.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../applications/ManageApplication.php"><i class="fa-solid fa-file-signature me-2"></i>Manage Applications</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'CreateTestdate.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../physicaltestcall/CreateTestdate.php"><i class="fa-solid fa-file-signature me-2"></i>ManagePhysicalTest</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'QuestionPool.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../questionpool/QuestionPool.php"><i class="fa-solid fa-circle-question me-2"></i>Question Pool</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'ManageTest.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../managetest/ManageTest.php"><i class="fa-solid fa-pen-to-square"></i> Manage Test</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'ManageResult.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../Testresult/ManageResult.php"><i class="fa-solid fa-chart-bar"></i> Manage Results</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'Online_Test_call.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../Onlinetestcall/Online_Test_call.php"><i class="fa-solid fa-computer"></i> Onlin test call</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'Announcement.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../announcement/Announcement.php"><i class="fa-solid fa-bullhorn me-2"></i> Announcement</a>
        <a class="nav-link text-dark rounded my-1 <?php if ($Current_page == 'Setting.php') echo 'active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold'; ?>" href="../Mysetting/Setting.php"><i class="fa-solid fa-gear me-2"></i> Setting</a>
        <a class="nav-link text-dark rounded my-1" href="../alluses/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </nav>

    </aside>