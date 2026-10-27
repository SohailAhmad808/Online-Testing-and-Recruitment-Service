<?php
  require_once '../connect/connect.php';

    // Applying to the specific post will here
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])){
    $post_id = intval($_POST['post_id']);
     // Check if user already applied
    $sql_check = "SELECT * FROM applications WHERE id = ? AND post_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param(
      "ii",
      $user_id,
      $post_id
    );
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    if($result->num_rows > 0){
        echo "<script>alert('You have already applied for this post!')</script>";
        echo "<script>window.location.href='UserApply.php';</script>";
        exit();
    }
    $stmt_check->close();
    // Insert new application
    $sql = "INSERT INTO applications (post_id, id, status)
                VALUES (?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
          "ii",
          $post_id,
          $user_id
        );
        if ($stmt->execute()) {
            // 3️⃣ Get auto-generated application_id
            $application_id = $conn->insert_id;

            // 4️⃣ Generate roll number using application_id
            $rollno = "CAND_" . $application_id;

            // 5️⃣ Update roll number
            $sql_update = "UPDATE applications SET rollno = ? WHERE application_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $rollno, $application_id);
            $stmt_update->execute();
            $stmt_update->close();
            echo "<script>alert('Application submitted successfully!')</script>";
            echo "<script>window.location.href='UserApply.php';</script>";
        } else {
            echo "<script>alert('Something went wrong, try again!')</script>";
            echo "<script>window.location.href='UserApply.php';</script>";
        }

        $stmt->close();
  }



  // Fetching data from the table posts
  $sql = "SELECT * FROM posts ORDER BY post_id DESC";
  $statment = $conn-> prepare($sql);
  $statment -> execute();
  $result = $statment->get_result();
  $statment->close();
  // Fetching data from the table application
  $sql2 = "SELECT 
    a.application_id,
    a.post_id,
    a.id AS user_id,
    a.apply_date,
    a.status,
    a.rollno,
    p.title
FROM applications a
JOIN posts p 
    ON a.post_id = p.post_id
WHERE a.id =?
ORDER BY a.application_id DESC";
  $statment2 = $conn-> prepare($sql2);
  $statment2->bind_param("i", $user_id);
  $statment2 -> execute();
  $result2 = $statment2->get_result();
  $statment2->close();

  // Getting currecnt date
  $current_date = date('Y-m-d');
  

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
      Convas Start Here
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">
     <div class="row">
      <div class="card">
        <div class="card-header">
          applied Post shown here
        </div>
        <div class="card-body">
          
            <table class="table table-hover table-sm">
              <thead>
                <tr>
                  <th scope="col">Title</th>
                  <th scope="col">Date of apply</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  while($row = $result2->fetch_assoc()){
                ?>
                <tr>
                  <td><?php echo $row['title']; ?></td>
                  <td><?php echo $row['apply_date']; ?></td>
                  <td><?php echo $row['status']; ?></td>
                </tr>
                <?php
                  }
                ?>
                
              </tbody>
            </table>
            
        </div>
      </div>
      <div class="bg-primary">
        <h3 class="text-center my-2 my-md-4 ">Open post Are here</h3>
      </div>
      <?php
        if ($result && $result->num_rows > 0){
          while ($row = $result->fetch_assoc()){
            $today = new DateTime();
            $deadline = new DateTime($row['deadline']);

            if ($deadline < $today) {
                continue; // Skip expired post
            }


      ?>
        <div class="col-sm-6 mb-1">
            <div class="card text-center bg-secondary-subtle">
              <div class="card-header"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="card-body">
                    <p class="card-text"><span class="fw-bold">Department :</span> <?php echo htmlspecialchars($row['categery']); ?></p>
                    <p class="card-text"><span class="fw-bold">Apply before :</span><?php echo htmlspecialchars($row['deadline']); ?></p>
                    <p class="card-text"><div class="fw-bold">Description :</div><?php echo htmlspecialchars($row['post_description']); ?></p>
                    <p class="card-text"><span class="fw-bold">Post Type :</span>
                    <?php if ($row['typ'] == 'cbt') {
                          echo "Computer Based Test";
                      } elseif ($row['typ'] == 'pbt') {
                          echo "Physical Based Test";
                      } else {
                          echo "Unknown";
                      } ?>
                    </p>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                      <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                      <button type="submit" name="apply" class="btn btn-outline-primary">
                        Apply
                      </button>
                    </form>

                </div>
                <div class="card-footer text-muted">
                    <?php
                    $today = new DateTime();
                  $deadline = new DateTime($row['deadline']);
                  $daysLeft = $today->diff($deadline)->days;

                  echo ($today <= $deadline) 
                      ? $daysLeft . " days remaining" 
                      : "Closed";

                    ?>
                </div>
            </div>
        </div>
        <?php
            }
         }
        ?>
        
      </div>
        

    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>