<?php
  include_once 'connection.php';
  // Fetching data from the table posts
  $sql = "SELECT * FROM posts ORDER BY post_id DESC";
  $statment = $conn-> prepare($sql);
  $statment -> execute();
  $result = $statment->get_result();
  $statment->close();

  $current_date = date('Y-m-d');
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

  <div class="row">
    <?php
        if ($result && $result->num_rows > 0){
          while ($row = $result->fetch_assoc()){
            if ($row['deadline'] < $current_date) {
            // Skip this post because the deadline has passed
            continue;
        }

      ?>
        <div class="col-sm-6 mb-1">
            <div class="card bg-secondary-subtle">
              <div class="card-header"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="card-body">
                    <p class="card-text"><span class="fw-bold">Department :</span> <?php echo htmlspecialchars($row['categery']); ?></p>
                    <p class="card-text"><span class="fw-bold">Apply before :</span><?php echo htmlspecialchars($row['deadline']); ?></p>
                    <p class="card-text"><span class="fw-bold">Description :</span><?php echo htmlspecialchars($row['post_description']); ?></p>
                    <p class="card-text"><span class="fw-bold">Post Type :</span>
                    <?php if ($row['typ'] == 'cbt') {
                          echo "Computer Based Test";
                      } elseif ($row['typ'] == 'pbt') {
                          echo "Physical Based Test";
                      } else {
                          echo "Unknown";
                      } ?>
                    </p>
                    <p class="card-text"><span class="fw-bold">requirement :</span> FSC pass</p>
                    <div class="text-center">
                      <a href="../Login&Registration/UserLogin.php" class="btn btn-primary" target="_blank">Apply</a>
                    </div>
                    

                </div>
                <div class="card-footer text-muted">
                    2 days ago
                </div>
            </div>
        </div>
        <?php
            }
         }
        ?>
    <div>



  <!-- Footer -->
   <?php
      require_once 'MainFooter.php';
   ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
