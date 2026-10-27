
    
    <header class="border-bottom  bg-opacity-50 sticky-top bg-primary-subtle  mb-1" >
      <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
          
          <!-- Left side (Logo + Title) -->
          <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start mb-0">
            <img src="../../Picture/Sproject.png" alt="Company Profile" 
                class="object-fit-cover rounded-4 m-2 d-none d-md-block border border-primary" 
                style="height: 60px; width: 60px;">
            <div class="text-center">
              <h5 class="mb-0 fw-bold fs-6 fs-md-4 ">Educational Testing</h5>
              <small class="text-muted">Educational Testing Made Easy</small>
            </div>
          </div>
          
          <!-- Right side (User + Dropdown) -->
          <div class="col-12 col-md-6 d-flex align-items-center justify-content-md-end justify-content-between">
            <div class="m-md-2">
              <button class="hamburger-btn d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <span><i class="fa fa-bars" style="color:white; background-color:#6D28D9;"></i></span>
              </button>
            </div>
            <div class="d-flex align-items-center justify-content-center m-md-2">
              <div class="dropdown me-2">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="fs-6"><?php echo htmlspecialchars($firstname) ?></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                  <li><a class="dropdown-item" href="../profile/UserProfile.php">My Profile</a></li>
                  <li><a class="dropdown-item" href="../setting/UserSetting.php">Change Password</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger" href="../login/logout.php">Logout</a></li>
                </ul>
              </div>
              
            </div>
          </div>
        </div>
      </div>     
    </header>