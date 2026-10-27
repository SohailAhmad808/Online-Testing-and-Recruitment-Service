<?php
    require_once '../connect/connect.php';
    
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <link rel="stylesheet" href="../../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
  </head>
  <body>  
    <!--
          Header
          -->
          <?php
          require '../others/UserHeader.php';
          ?>
      <!--
      Sidebar Start Here
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">
      <div class="container-fluid">
        <div class="row">

          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../profile/UserProfile.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-user fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">UPDATE PROFILE</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1 ">
            <a href="../education/Education.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-graduation-cap fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">EDUCATION</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../test/UserTest.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-pen-to-square fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">MY TEST</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../result/UserResult.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">RESULTS</h6>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>

    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>