<?php
  require_once '../connect/connect.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_btn'])){
    
    // initialize values 
    $degree = trim($_POST['degree']) ?? '';
    $board = trim($_POST['board']) ?? '';
    $obtain_mark = trim($_POST['obtain_mark']) ?? '';
    $max_mark = trim($_POST['max_mark']) ?? '';
    $grade = trim($_POST['grade']) ??'';

    // Input input validity
    if ($degree === '' || $board === '') {
        header("Location: Education.php?error=empty_fields");
        exit();
    }
    
    // Check if degree already exists for this user
      $checkSql = "SELECT education_id FROM education WHERE id = ? AND degree = ?";
      $checkStmt = $conn->prepare($checkSql);
      $checkStmt->bind_param("is", $user_id, $degree);
      $checkStmt->execute();
      $checkResult = $checkStmt->get_result();

      if ($checkResult->num_rows > 0) {
          // Degree already exists
          echo "<script>alert('This Degree that you are trying to entering already exist'); window.location.href = 'Education.php'; </script>";
          exit();
      }

    $sql ="INSERT INTO education (id, degree, board, obtain_mark, max_mark, grade) 
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param(
      "isssss",
      $user_id,
      $degree,
      $board,
      $obtain_mark,
      $max_mark,
      $grade
    );
    if ($stmt->execute()) {
        header("Location: Education.php?success=1");
        exit();
    } else {
      
        error_log($stmt->error);
        header("Location: Education.php?error=db_error");
        exit();
    }
  }

  // Deleting the one education row form the table
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["delete_id"])){
    
    // Converting into safe integer
    $delete_id = intval($_POST['delete_id']);

    // Deletiing query
    $deleteSql = "DELETE FROM education WHERE education_id = ? AND id = ?";
    $delStmt = $conn->prepare($deleteSql);
    $delStmt->bind_param("ii", $delete_id, $user_id);

    if ($delStmt->execute()) {
        header("Location: education.php?deleted=1");
        exit();
    } else {
        error_log($delStmt->error);
        header("Location: education.php?error=delete_failed");
        exit();
    }

  }

  // Used for inserting for education in the page
  $sql2 = "SELECT * FROM education WHERE id = ? ";
  $statment2 = $conn ->prepare($sql2);
  $statment2 -> bind_param(
    "i",
    $user_id
  );
  $statment2 -> execute();
  $result2 = $statment2->get_result();


  // It is used to check if one education is entered multiple time or not?
  $sql3 = "SELECT degree FROM education WHERE id = ?";
  $statment3 = $conn -> prepare($sql3);
  $statment3 -> bind_param(
    "i",
    $user_id
  );
  $statment3 -> execute();
  $result3 = $statment3->get_result();
  while($checkdegree = $result3 ->fetch_assoc()){
    $existingDegree[] = $checkdegree['degree'];
  }
  
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
      Sidebar
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="fw-bold fs-4">Education</div>
                    <button class="btn btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        + Education
                    </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Please enter your highest Education</h5>
                    <div class="row card-text my-2">
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> SSC/Matriculation</div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> HSSC/Intermidiate</div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> Bachelor Degree(14)</div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> Bachelor Degree(16)</div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> Master/MS/M-Phil Degree</div>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-md-xl-2 p-1 border rounded-3 g-2 fw-light">>> Doctoriate of Philosophy(PhD)</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered rounded-2">
                            <caption>Here's your Academic Details</caption>
                            <thead class="table-light">
                                <tr>
                                <th scope="col">Degree/Certificate</th>
                                <th scope="col">Board/University</th>
                                <th scope="col">CGPA/Marks</th>
                                <th scope="col">Max Marks</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              while($row = $result2->fetch_assoc()){
                              ?>
                                <tr>
                                  <td><?php echo $row['degree'] ; ?></td>
                                  <td><?php echo $row['board'] ; ?></td>
                                  <td><?php echo $row['obtain_mark'] ; ?></td>
                                  <td><?php echo $row['max_mark'] ; ?></td>
                                  <td><?php echo $row['grade'] ; ?></td>
                                  <td>
                                    <form action="education.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['education_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form></td>
                                </tr>
                                <?php
                              }
                                ?>
                            </tbody>
                        </table>
                    </div>

                  <!-- Modal -->
                  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title text-primary fw-bold lead" id="staticBackdropLabel">My Education</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" id="eduForm" method="POST">

                            <div class="row g-3">
                              <div class="col-md-6">
                                <label for="form-label">Degree/Certificate</label>
                                <select class="form-select" name="degree" id="MyDegree" required>
                                  <option disabled selected>.. selected ..</option>
                                  <option value="Matriculation"  >Matriculation</option>
                                  <option value="Intermediate" >Intermediate</option>
                                  <option value="BS 14 Year" >BS 14 Year</option>
                                  <option value="BS 16 Year" >BS 16 Year</option>
                                  <option value="M-Phil" >M-Phil</option>
                                  <option value="PhD" >PhD</option>
                                </select>
                              </div>
                              <div class="col-md-6">
                                <label for="">Board/University</label>
                                <input type="text" class="form-control" name="board" required>
                              </div>
                              
                              <div class="col-md-6">
                                <label for="">Obtain Marks/CGPA</label>
                                <input type="text" class="form-control" name="obtain_mark" required>
                              </div>
                              <div class="col-md-6">
                                <label for="">Max Marks/CGPA</label>
                                <input type="text" class="form-control" name="max_mark">
                              </div>
                              <div class="col-md-6">
                                <label for="Mygrade" class="form-label">Grade</label>
                                <select name="grade" for="" class="form-select" id="Mygrade" required>
                                  <option disabled selected>select grade</option>
                                  <option value="A1">A1</option>
                                  <option value="A">A</option>
                                  <option value="B">B</option>
                                  <option value="C">C</option>
                                  <option value="D">D</option>
                                  <option value="E">E</option>
                              </select>
                              </div>
                            </div>
                          </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-danger"  data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-outline-success" name="insert_btn" form="eduForm">Save Information</button>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </main>
  </div>
  
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>


