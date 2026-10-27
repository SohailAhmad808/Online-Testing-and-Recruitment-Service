  
  <!-- Loaded page Location -->
  <?php
      $Current_page= basename($_SERVER['PHP_SELF']);
      // Fetch user's current profile image
      $user_sql = "SELECT profile_image,fname, lname FROM candidate WHERE id = ?";
      $userstmt = $conn->prepare($user_sql);
      $userstmt->bind_param(
        "i",
        $user_id
      );
      $userstmt->execute();
      $userResult = $userstmt->get_result();

      $currentImage = "";
      if ($userResult->num_rows > 0) {
          $row = $userResult->fetch_assoc();
          $currentImage = $row['profile_image'];
          $user_name = $row['fname'];
      }
    ?>
<div class="offcanvas offcanvas-start" 
      data-bs-scroll="true" 
      tabindex="-1" 
      id="sidebarOffcanvas" 
      aria-labelledby="offcanvasWithBackdropLabel"
      style="--bs-offcanvas-width: 250px;">

      
      <div class="offcanvas-body p-0">
        <div class="p-3 text-center">
          <img src="
          <?php
            if(!empty($currentImage)){
                echo '../uploads/' . $currentImage;
            }
            else{

            }
          ?>
          " 
          alt="<?php echo $user_name ?>" 
          class="object-fit-cover rounded-circle" 
          style="width: 100px; height: 100px;">
          <div class="fw-semibold"><?php echo $user_name ?></div>
        </div>
        <nav class="nav flex-column p-2">
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'AdminDashboard.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../dashboard/UserDashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserProfile.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../profile/UserProfile.php"><i class="fa-solid fa-user"></i> Profile</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserProfileimage.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../useruploadpicture/UserProfileimage.php"><i class="fa-solid fa-circle-user"></i> Image</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'Education.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../education/Education.php"><i class="fa-solid fa-graduation-cap"></i> Education</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserApply.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../userpost/UserApply.php"><i class="fa-solid fa-briefcase"></i> Apply</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'RollNumberSlip.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../rollnumber/RollNumberSlip.php"><i class="fa-solid fa-id-card"></i> Roll Number</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserTest.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../test/UserTest.php"><i class="fa-solid fa-pen-to-square"></i> Tests</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserResult.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../result/UserResult.php"><i class="fa-solid fa-chart-bar"></i> Results</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserNotification.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../notification/UserNotification.php"><i class="fa-solid fa-bell"></i> Notifications</a>
          <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserSetting.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../setting/UserSetting.php"><i class="fa-solid fa-gear"></i> Settings</a>
          <a class="nav-link text-dark rounded my-1" href="../login/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav> 
      </div>
    </div>

    <!--
    Aside
    -->
    <div class="row">
      <aside class="d-none d-md-block col-md-3 col-lg-2"
      style="position: sticky; top: 0; height: 100vh; overflow-y: auto;">
        <div class="p-2 text-center">
          <img src="
          <?php
            if(!empty($currentImage)){
                echo '../uploads/' . $currentImage;
            }
            else{
              echo "https://via.placeholder.com/150?text=Profile";
            }
          ?>
          " 
          alt="<?php echo $user_name ?>" 
          class="object-fit-cover rounded-circle" 
          style="width: 90px; height: 90px;">
          <div class="fw-semibold"><?php echo $user_name ?></div>
        </div>
      <nav class="nav flex-column p-2">
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserDashboard.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../dashboard/UserDashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserProfile.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../profile/UserProfile.php"><i class="fa-solid fa-user"></i> Profile</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserProfileimage.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../useruploadpicture/UserProfileimage.php"><i class="fa-solid fa-circle-user"></i> Image</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'Education.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../education/Education.php"><i class="fa-solid fa-graduation-cap"></i> Education</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserApply.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../userpost/UserApply.php"><i class="fa-solid fa-briefcase"></i> Apply</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'RollNumberSlip.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../rollnumber/RollNumberSlip.php"><i class="fa-solid fa-id-card"></i> Roll Number</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserTest.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../test/UserTest.php"><i class="fa-solid fa-pen-to-square"></i> Tests</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserResult.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../result/UserResult.php"><i class="fa-solid fa-chart-bar"></i> Results</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserNotification.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../notification/UserNotification.php"><i class="fa-solid fa-bell"></i> Notifications</a>
        <a class="nav-link text-dark rounded my-1 <?php if($Current_page == 'UserSetting.php'){ echo "active bg-primary bg-opacity-50 text-white rounded my-1 fw-semibold"; } ?>" href="../setting/UserSetting.php"><i class="fa-solid fa-gear"></i> Settings</a>
        <a class="nav-link text-dark rounded my-1" href="../login/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </nav>

    </aside>