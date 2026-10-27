<?php
    include_once 'connection.php';
    // Getting announcement data from the annoncement table
    $announcement_sql = "SELECT * FROM announcements ORDER BY announcement_id DESC";
    $announcement_stmt = $conn->prepare($announcement_sql);
    $announcement_stmt->execute();
    $announcement_result = $announcement_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Testing and Recruitment</title>
  <link rel="icon" type="image/x-icon" href="../Picture/Sproject.png">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>
<body>
  <!-- Header -->
   <?php
    require_once 'MainHeader.php';
   ?>

   <!-- Body -->
     <!-- Optional: List of Previous Announcements -->
              <div class="card mt-2 border-0 shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between">
                  <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Previous Announcements</h6>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <?php
                      if($announcement_result->num_rows > 0 ):
                        while($announcement_rowbyrow = $announcement_result->fetch_assoc()):
                    ?>
                    <div class="list-group-item my-1 border border-danger border-1">
                      <h6 class="mb-1"><?php echo $announcement_rowbyrow['title']; ?></h6>
                      <p class="mb-1 text-muted"><?php echo $announcement_rowbyrow['content']; ?></p>
                      <small class="text-secondary">Posted on: <?php echo $announcement_rowbyrow['created_at']; ?></small>
                    </div>
                    <?php 
                        endwhile;
                      endif;
                    ?>
                  </div>
                </div>
              </div>
          </div>

  <!-- Footer -->
   <?php
      require_once 'MainFooter.php';
   ?>

  <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
