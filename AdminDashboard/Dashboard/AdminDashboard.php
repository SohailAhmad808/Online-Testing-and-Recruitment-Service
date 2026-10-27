<?php
require_once '../Connect.php';
$sql1 = "SELECT * FROM candidate";
$statement1 = $conn->prepare($sql1);
$statement1 -> execute();
$result1 = $statement1->get_result();
$rowCount1 = $result1->num_rows;

$sql2 = "SELECT * FROM posts";
$statement2 = $conn->prepare($sql2);
$statement2 -> execute();
$result2 = $statement2 -> get_result();
$rowCount2 = $result2->num_rows;

$sql3 = "SELECT * FROM applications";
$statement3 = $conn->prepare($sql3);
$statement3 -> execute();
$result3 = $statement3 -> get_result();
$rowCount3 = $result3->num_rows;

$sql4 = "SELECT * FROM quiz_results";
$statement4 = $conn->prepare($sql4);
$statement4 -> execute();
$result4 = $statement4 -> get_result();
$rowCount4 = $result4->num_rows;

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
    <!--     Admin Header bar    -->
     <?php
        require_once '../alluses/AdminHeader.php';
     ?>
     <!--   Admin Sidebar   -->
      <?php
        require_once '../alluses/AdminSidebar.php';
      ?>
    
    
    <main class="col-md-9 col-lg-10">
      <div class="container-fluid">
        <div class="row my-4">
          <div class="col-md-6 col-lg-6 col-xl-4 py-1 my-2">
           
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><?php echo $rowCount1; ?></div>
                  <h6 class="card-title border border-success m-0 py-4 border-1 bg-secondary bg-opacity-50">Number of Candidates</h6>
                </div>
              </div>
            
          </div>
          <div class="col-md-6 col-lg-6 col-xl-4 py-1 my-2">
            
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><?php echo $rowCount2; ?></div>
                  <h6 class="card-title border border-success m-0 py-4 border-1 bg-secondary bg-opacity-50">Number of Posts</h6>
                </div>
              </div>
            
          </div>
          <div class="col-md-6 col-lg-6 col-xl-4 py-1 my-2">
            <a href="#" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><?php echo $rowCount3; ?></div>
                  <h6 class="card-title border border-success m-0 py-4 border-1 bg-secondary bg-opacity-50">Candidate application</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-lg-6 col-xl-4 py-1 my-2">
            <a href="#" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><?php echo $rowCount4; ?></div>
                  <h6 class="card-title border border-success m-0 py-4 border-1 bg-secondary bg-opacity-50">No candidate attempt tests</h6>
                </div>
              </div>
            </a>
          </div>
        </div>


      <!--       Another  -->
        <br>
        <div class="row my-3 mb-5">
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../usermanagement/UserManagement.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-user fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">User Management</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1 ">
            <a href="../applications/ManageApplication.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-graduation-cap fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Manage applications</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../managetest/ManageTest.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-pen-to-square fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Manage physical test</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../questionpool/QuestionPool.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Question pool</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../managetest/ManageTest.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Create question paper</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../Testresult/ManageResult.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Result management</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../Onlinetestcall/Online_Test_call.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">Online test call</h6>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 py-1">
            <a href="../announcement/Announcement.php" class="text-decoration-none">
              <div class="card border text-center shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                  <div class="p-4"><i class="fa-solid fa-chart-bar fa-3x text-primary text-opacity-50"></i></div>
                  <h6 class="card-title border border-success m-0 py-3 border-1 bg-primary bg-opacity-50">See all announcement</h6>
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