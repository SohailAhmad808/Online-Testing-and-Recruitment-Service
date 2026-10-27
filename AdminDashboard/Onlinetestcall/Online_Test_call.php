<?php
  require_once '../Connect.php';
  // ====================== DELETE QUESTION PAPER ===========================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deletequestionroll'])) {

    $roll_id = intval($_POST['roll_id']);

    if (empty($roll_id)) {
        echo "<script>alert('Invalid Roll ID');window.location='Online_Test_call.php';</script>";
    } else {
        $delete_sql = "DELETE FROM rollcreater WHERE roll_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $roll_id);

        if ($stmt->execute()) {
            echo "<script>alert('Record Deleted Successfully'); window.location='Online_Test_call.php';</script>";
        } else {
            echo "<script>alert('Failed to delete');</script>";
        }
    }
}

  // ====================== UPDATE QUESTION PAPER ===========================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['updatequestionroll'])) {

    $roll_id = intval($_POST['oqc_id']);
    $pass_percentage = floatval($_POST['Passpercentage']);
    $test_center = trim($_POST['test_center']);
    $test_date = trim($_POST['test_date']);
    $reporting_time = trim($_POST['reporting_time']);

    if (empty($roll_id) || empty($test_center) || empty($pass_percentage) || empty($test_date) || empty($reporting_time)) {
        echo "<script>alert('All fields are required!');window.location='Online_Test_call.php';</script>";
    } else {
        $update_sql = "UPDATE onlinequizcall 
                       SET otest_center = ?, 	otest_date = ?, oreporting_time = ?, passpercentage = ?
                       WHERE oqc_id = ?";

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssdi", $test_center, $test_date, $reporting_time, $pass_percentage, $roll_id);

        if ($stmt->execute()) {
            echo "<script>alert('Record Updated Successfully'); window.location='Online_Test_call.php';</script>";
        } else {
            echo "<script>alert('Failed to update');window.location='Online_Test_call.php';</script>";
        }
    }
}

  // Creating new Question paper based Roll number for the users
  if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_qpaper'])){
    $qp_id = intval($_POST['JobSelect']);
    $test_center = trim($_POST['test_center']) ?? '';
    $test_date = trim($_POST['test_date']) ?? '';
    $reporting_time_input = trim($_POST['report_time']) ?? '';
    $passpercentage = floatval($_POST['passpercentage']);
    $reporting_time = '';
    if (!empty($reporting_time_input)) {
        // Convert to time format
        $reporting_time = $reporting_time_input.":00";
        
    }
    if(empty($qp_id) || empty($test_center) || empty($test_date) || empty($reporting_time || empty($passpercentage))){
        die("Sohail");
      header("Location: ManagePost.php?error=empty_fields");
      exit();
    }else{
    $sql1 ="INSERT INTO onlinequizcall (qp_id, otest_center, otest_date, oreporting_time, passpercentage)
                        VALUES(?, ?, ?, ?, ?)";
    $sql_stmt = $conn->prepare($sql1);
    $sql_stmt->bind_param("isssd",
    $qp_id,
    $test_center,
    $test_date,
    $reporting_time,
    $passpercentage
  );
   if( $sql_stmt->execute()){
    echo "<script>alert('Paper Created Successfully'); window.location='Online_Test_call.php';</script>";


   }
    }

  }
  // Getting the all post
  $myposts_sqll = "SELECT qp_id, qp_title FROM question_papers";
  $mypost_stmt = $conn->prepare($myposts_sqll);
  $mypost_stmt->execute();
  $result = $mypost_stmt->get_result();
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
            <div class="row">
                <div class="card">
                    <div class="card-header bg-primary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fs-4">Online Test Call</h3>
                            <button class="btn btn-outline-primary btn-sm openCreateModal"
                              data-bs-toggle="modal" data-bs-target="#CreatingQuestionPaper">
                              + Create new
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="CreatingQuestionPaper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="fa-solid fa-file-pen me-2"></i> Create Question paper</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="OnlineTestCalling" method="POST">
                            <div class="row">
                                <div class="col-md-6 my-2">
                                  
                                    <label for="SelectPost" class="form-label">Select Post</label>
                                    <select name="JobSelect" id="SelectPost" class="form-select" required>
                                      <option selected disabled>--Select title--</option>
                                      <?php
                                          while($row = mysqli_fetch_assoc($result)){

                                        ?>
                                      <option value="<?php echo $row['qp_id']; ?>"><?php echo $row['qp_title'];  ?></option>
                                      <?php 
                                          }
                                      ?>
                                    </select>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="testingcenter" class="form-label">Test Center</label>
                                    <input type="text" id="testingcenter" class="form-control" name="test_center" placeholder="e.g 20" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="testing_date" class="form-label">Test Date</label>
                                    <input type="date" id="testing_date" class="form-control" name="test_date" placeholder="e.g 30" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="reporting_time" class="form-label">Reporting Time</label>
                                    <input type="time" id="reporting_time" name="report_time" class="form-control">
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="passingpercent" class="form-label">Pass passpercentage</label>
                                    <input type="text" id="passingpercent" class="form-control" name="passpercentage" placeholder="e.g 20" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" name="create_qpaper" form="OnlineTestCalling"  class="btn btn-outline-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Create Paper</button>
                          </div>

                        </form>
                    </div>
                    
                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php 
                  // Fetch all question papers
                  
                    $papers_sql = "SELECT otr.*, p.qp_title AS post_title
                      FROM onlinequizcall otr
                      JOIN question_papers p ON p.qp_id = otr.qp_id
                      ORDER BY otr.oqc_id DESC";
                      
                    $papers_stmt = $conn->prepare($papers_sql);
                    $papers_stmt->execute();
                    $papers_result = $papers_stmt->get_result();
                  ?>
        <div class="accordion accordion-flush" id="accordionFlushExample">
           <?php
              $counter = 1;
              while($paper = $papers_result->fetch_assoc()) {
                        
            ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="<?= $counter ?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $counter ?>" aria-expanded="false" aria-controls="flush-collapse<?= $counter ?>">
                <?php 
                  echo $paper['post_title'] . " (Test time No #" . $counter . ")"; 
                ?>
              </button>
            </h2>
            <div id="flush-collapse<?= $counter ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $counter ?>" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <div class="container ">
                  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <p><strong>Paper Title:</strong><input type="text" id="testtitle" class="form-control" name="test_title" value="<?= $paper['post_title'] ?>" placeholder="e.g 20" disabled required> </p>
                        <p><strong>Test Center:</strong><input type="text" id="testingcenter" class="form-control" name="test_center" value="<?= $paper['otest_center'] ?>" placeholder="e.g 20" required></p>
                        <p><strong>Test Date:</strong>
                        <input type="date" 
                          id="testing_date" 
                          class="form-control" 
                          name="test_date" 
                          value="<?= htmlspecialchars(date('Y-m-d', strtotime($paper['otest_date']))) ?>" 
                          required>
                        </p>
                        <p><strong>Test Time:
                          <input type="time" 
                          id="reporting_time" 
                          class="form-control" 
                          name="reporting_time" 
                          value="<?= htmlspecialchars(substr($paper['otest_time'], 0, 5)) ?>" 
                          required>
                        </p>
                        <p><strong>Pass passpercentage:</strong>
                        <input type="text" 
                          id="passpercentage" 
                          class="form-control" 
                          name="Passpercentage" 
                          value="<?= $paper['passpercentage'] ?>" 
                          required>
                        </p>
                        <input type="hidden" name="oqc_id" value="<?= $paper['oqc_id'] ?>">
                        <div class="text-center">
                          <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="submit" class="btn btn-success" name="updatequestionroll">Update</button>
                            
                            <button type="submit" class="btn btn-danger" name="deletequestionroll">Delete</button>
                          </div>
                        </div>
                        
                </form>
                </div>
              </div>
            </div>
          </div>
          <?php
            $counter++;
          }
          ?>
        </div>


    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>

  </body>
</html>