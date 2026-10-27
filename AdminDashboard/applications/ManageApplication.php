<?php
  require_once '../Connect.php';
  // Fetching data from the applications table
  $sql = "SELECT 
    a.*,
    p.title,
    c.fname,
    c.email
FROM applications a
JOIN posts p 
    ON a.post_id = p.post_id
JOIN candidate c
    ON a.id = c.id
ORDER BY a.application_id DESC";
  $statement = $conn->prepare($sql);
  $statement -> execute();
  $result = $statement->get_result();


    // Deleting the one education row form the table
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["delete_id"])){
    
    // Converting into safe integer
    $delete_id = intval($_POST['delete_id']);

    // Deletiing query
    $deleteSql = "DELETE FROM applications WHERE application_id = ?";
    $delStmt = $conn->prepare($deleteSql);
    $delStmt->bind_param(
      "i",
      $delete_id
    );

    if ($delStmt->execute()) {
        header("Location: ManageApplication.php?deleted=1");
        exit();
    } else {
        error_log($delStmt->error);
        header("Location: ManageApplication.php?error=delete_failed");
        exit();
    }

  }
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
      $app_id = intval($_POST['application_id']);
      $status = $_POST['status'];

      $update_sql = "UPDATE applications SET status = ? WHERE application_id = ?";
      $update_stmt = $conn->prepare($update_sql);
      $update_stmt->bind_param("si", $status, $app_id);

      if ($update_stmt->execute()) {
          echo "<script>alert('Status updated successfully!');</script>";

          // ✅ Refresh without POST duplication
          echo "<script>window.location.href='".$_SERVER['PHP_SELF']."';</script>";
          exit;
      } else {
          echo "<script>alert('Error updating status');</script>";
      }
  }


  $user_data = null;
  $education_data = null;
  $applications_data = null;
  $modal_show = false;
  $result_data = null;
  if (isset($_GET['view_profile'])){
    $modal_show = true;
    
    // geting the data of student to show insight
    $user_id = intval($_GET['view_profile']);

    //Getting profile information
    $user_profile_sql = "SELECT * FROM candidate WHERE id = ?";
    $user_stmt = $conn->prepare($user_profile_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    // Getting User Education
    $edu_sql = "SELECT * FROM education WHERE id = ?";
    $edu_stmt = $conn->prepare($edu_sql);
    $edu_stmt->bind_param("i", $user_id);
    $edu_stmt->execute();
    $education_data = $edu_stmt->get_result();
    // Getting user Apply for how many post
    $app_sql = "
    SELECT a.*, p.title, p.typ
    FROM applications a
    INNER JOIN posts p 
        ON a.post_id = p.post_id
    WHERE a.id = ?
";
    $app_stmt = $conn->prepare($app_sql);
    $app_stmt->bind_param("i", $user_id);
    $app_stmt->execute();
    $applications_data = $app_stmt->get_result();
    // Getting the user test results
        $Result_query = "
            SELECT 
                tr.*, 
                u.fname AS first_name,
                u.id,
                u.lname As last_name
            FROM quiz_results tr
            JOIN candidate u ON u.id = tr.id
            WHERE tr.id = ?
            ORDER BY tr.attempt_date DESC
        ";
        $Result_stmt = $conn->prepare($Result_query);
        $Result_stmt->bind_param("i",$user_id);
        $Result_stmt->execute();
        $result_data = $Result_stmt->get_result();
    
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
    <!--     Admin Header bar    -->
     <?php
        require_once '../alluses/AdminHeader.php';
     ?>
     <!--   Admin Sidebar   -->
      <?php
        require_once '../alluses/AdminSidebar.php';
      ?>
    <main class="col-md-9 col-lg-10">
        <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Post Name</h5>
                <h6 class="card-subtitle mb-2 text-muted">Applied Users</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Applicant_Name</th>
                            <th scope="col">Applied for</th>
                            <th scope="col">Roll Number</th>
                            <th scope="col">Date of Apply</th>
                            <th scope="col">Status</th>
                            
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php
                              $no = 1;
                              while($row = $result->fetch_assoc()){
                              ?>
                                <tr>
                                  <td> <?= $no++; ?></td>
                                  <td><?= $row['fname'] ?></td>
                                  <td><?= $row['title'] ?></td>
                                  <td><?= $row ['rollno'] ?></td>
                                  <td><?= $row['apply_date']; ?></td>
                                  <td>
                                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                                          <input type="hidden" name="application_id" value="<?= $row['application_id']; ?>">

                                          <select class="form-select" name="status" required>
                                              <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                              <option value="Accepted" <?= ($row['status'] == 'Accepted') ? 'selected' : ''; ?>>Accepted</option>
                                              <option value="Rejected" <?= ($row['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                          </select>

                                          <button type="submit" class="btn btn-success btn-sm mt-1" name="update_status">
                                            Update
                                          </button>
                                      </form>
                                    </td>
                                    
                                    <td>
                                      <div class="btn-group" role="group" aria-label="Basic example">
                                          <!-- View button -->
                                          <a href="?view_profile=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa-solid fa-eye" style="cursor:pointer;"></i></a>
                                          <!-- Delete buttton -->
                                          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                              <input type="hidden" name="delete_id" value="<?php echo $row['application_id']; ?>">
                                              <button type="submit" class="btn btn-danger"><i class="fas fa-trash " style="cursor:pointer;"></i></button>
                                          </form>
                                      </div>
                                    </td>
                                </tr>
                                <?php
                              }
                              ?>
                            </tbody>
                          </table>
                      </div>
                
                    </div>
                </div>
              </div>
        <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">

                                    <!--  Tabe  -->
                        <ul class="nav nav-tabs mb-3" id="userDetailsTabs">
                          <li class="nav-item">
                            <button class="nav-link active" id="profilepicture-tab" data-bs-toggle="tab" data-bs-target="#profilepicture" type="button">Profile Picture</button>
                          </li>
                          <li class="nav-item">
                            <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">Personal Info</button>
                          </li>
                          <li class="nav-item">
                            <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button">Education</button>
                          </li>
                          <li class="nav-item">
                            <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button">Job Applications</button>
                          </li>
                          <li class="nav-item">
                            <button class="nav-link" id="TEST-tab" data-bs-toggle="tab" data-bs-target="#TEST" type="button">ACTIVE RESULTS</button>
                          </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content">
                          <?php if(isset($user_data)): ?>
                            <!-- Profile Picture -->
                          <div class="tab-pane fade show active" id="profilepicture">
                            
                              <div class="row justify-content-center">
                                  <div class="col-md-8">
                                      <div class="card shadow-sm p-2 text-center">
                                          <!-- Profile Image Placeholder -->
                                          <div class="mb-3">
                                              <img id="previewImage" 
                                                  src="
                                                    <?php
                                                      if(!empty($user_data['profile_image'])){
                                                        echo '../../UserDashboard/uploads/' . $user_data['profile_image'];
                                                      }
                                                    ?>
                                                  "
                                                  class="rounded rounded-3 border"
                                                  style="width: 250px; height:250px; object-fit: cover;">
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                          <!-- Personal Info -->
                          <div class="tab-pane fade" id="personal">
                            <table class="table table-bordered">
                              
                              <tr><th>User Name</th><td><?php echo $user_data['fname']; ?></td></tr>
                              <tr><th>Father Name</th><td><?php echo $user_data['fathername']; ?></td></tr>
                              <tr><th>CNIC</th><td><?php echo $user_data['cnic']; ?></td></tr>
                              <tr><th>Email</th><td><?php echo $user_data['email']; ?></td></tr>
                              <tr><th>Phon Number</th><td><?php echo $user_data['phonnum']; ?></td></tr>
                              <tr><th>Gender</th><td><?php echo $user_data['gender']; ?></td></tr>
                              <tr><th>Date of Birth</th><td><?php echo $user_data['dob']; ?></td></tr>
                              <tr><th>Religion</th><td><?php echo $user_data['religion']; ?></td></tr>
                              <tr><th>WhatsApp no</th><td><?php echo $user_data['whatsappnum'] ?></td></tr>
                              <tr><th>Address</th><td><?php echo $user_data['uaddress']; ?></td></tr>
                              <tr><th>Marital Status</th><td><?php echo $user_data['maritalstatus']; ?></td></tr>
                              <tr><th>Hafiz Quran</th><td><?php echo $user_data['hafizquran']; ?></td></tr>
                            </table>
                          </div>

                          <!-- Education Info -->
                          <div class="tab-pane fade" id="education">
                            <?php if($education_data && $education_data->num_rows > 0): ?>
                            <?php while($edu = $education_data->fetch_assoc()): ?>
                            <table class="table table-striped  border border-3 border-dark">
                              <tbody>
                                <tr>
                                  <th>Education Title</th><td><?php echo $edu['degree']; ?></td>
                                </tr>
                                <tr>
                                  <th>Board/University</th><td><?php echo $edu['board']; ?></td>
                                </tr>
                                <tr>
                                  <th>Obtain Marks</th><td><?php echo $edu['obtain_mark']; ?></td>
                                </tr>
                                <tr>
                                  <th>Total Marks</th><td><?php echo $edu['max_mark']; ?></td>
                                </tr>
                                <tr>
                                  <th>Grade</th><td><?php echo $edu['grade']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                  <tr><td colspan="4" class="text-center">No education data available.</td></tr>
                                <?php endif; ?>
                                
                              </tbody>
                            </table>
                          </div>

                          <!-- Job Applications Info -->
                          <div class="tab-pane fade" id="applications">
                            <table class="table table-striped">
                              <thead class="table-secondary">
                                <tr>
                                  <th>Job Title</th>
                                  <th>Type</th>
                                  <th>Applied On</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if($applications_data && $applications_data->num_rows > 0): ?>
                                  <?php while($app = $applications_data->fetch_assoc()): ?>
                                    <tr>
                                      <td><?php echo htmlspecialchars($app['title']); ?></td>
                                      <td><?php echo htmlspecialchars($app['typ']); ?></td>
                                      <td><?php echo htmlspecialchars($app['apply_date']); ?></td>
                                      <td>
                                        <?php
                                          $status = $app['status'];
                                          $badgeClass = 'badge ';
                                          if(strtolower($status) == 'pending') {
                                            $badgeClass .= 'bg-warning text-dark';
                                          } elseif(strtolower($status) == 'accepted') {
                                            $badgeClass .= 'bg-success';
                                          } else {
                                            $badgeClass .= 'bg-secondary';
                                          }
                                        ?>
                                        <span class="<?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                                      </td>
                                    </tr>
                                  <?php endwhile; ?>
                                <?php else: ?>
                                  <tr><td colspan="3" class="text-center">No job applications found.</td></tr>
                                <?php endif; ?>
                                
                              </tbody>
                            </table>
                          </div>
                          <!-- Education Info -->
                          <div class="tab-pane fade" id="TEST">
                            <?php if($result_data->num_rows > 0): ?>
                            <?php while($UTEST = $result_data->fetch_assoc()): ?>
                          <div class="table-responsive">
                            <table class="table table-striped  border border-3 border-dark">
                              <tbody>
                                <tr>
                                  <th>Result id</th><td><?php echo $UTEST['result_id']; ?></td>
                                </tr>
                                <tr>
                                  <th>Question Paper id</th><td><?php echo $UTEST['qp_id']; ?></td>
                                </tr>
                                <tr>
                                  <th>Question Paper title</th><td><?php echo $UTEST['qp_title']; ?></td>
                                </tr>
                                <tr>
                                  <th>Obtained Marks</th><td><?php echo $UTEST['obtained_marks']; ?></td>
                                </tr>
                                <tr>
                                  <th>Total marks</th><td><?php echo $UTEST['total_marks']; ?></td>
                                </tr>
                                <tr>
                                  <th>Percentage</th><td><?php echo $UTEST['mpercentage']; ?></td>
                                </tr>
                                <tr>
                                  <th>Attempt Date</th><td><?php echo $UTEST['attempt_date']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                  <tr><td colspan="4" class="text-center">User did not appear any test.</td></tr>
                                <?php endif; ?>
                                
                              </tbody>
                            </table>
                          </div>
                          </div>
                          <?php endif; ?>
                        </div>

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                      </div>
                    </div>
                  </div>
                </div>
    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  <?php if($modal_show && $user_data): ?>
      <script>
          var userModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
          userModal.show();
          // ✅ Remove ?view_profile=ID from URL after modal opens
          if (window.history.replaceState) {
              window.history.replaceState(null, null, window.location.pathname);
          }
      </script>
      <?php endif; ?>
  </body>
</html>




