<?php 
  include_once '../Connect.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["post_announcement"])){
    $announcement = trim($_POST['announcement']);
    $announcement_message = trim($_POST['announcement_message']);
    //Input Validity
    if (!empty($announcement) && !empty($announcement_message)) {
        $sql = "INSERT INTO announcements (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
          "ss",
          $announcement,
          $announcement_message
        );
        if($stmt->execute()){
          
          echo "<script>alert('Announcement Added successfully!');</script>";

          // ✅ Refresh without POST duplication
          echo "<script>window.location.href='".$_SERVER['PHP_SELF']."';</script>";
          exit;
          // alert message here
        }else{
          
          $error = "Error: " . $stmt->error;
        }
        $stmt->close();
  }else{
    
    $error = "Please fill in all fields.";
  }
}
// Deleting the announcement row the the table
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["delete_announcement"])){
  // Getting the deleting id from post method
  $deleting_id = intval($_POST['announcement_id']);
  // Query starts here
  $deleting_query = "DELETE FROM announcements WHERE announcement_id = ?";
  $deleting_stmt = $conn->prepare($deleting_query);
  $deleting_stmt->bind_param(
    "i",
    $deleting_id
  );
  if($deleting_stmt->execute()){
    echo "<script>alert('Notification deleted successfully!');</script>";

    // ✅ Refresh without POST duplication
    echo "<script>window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    exit;
  }

}
  // Getting announcement data from the annoncement table
  $announcement_sql = "SELECT * FROM announcements";
  $announcement_stmt = $conn->prepare($announcement_sql);
  $announcement_stmt->execute();
  $announcement_result = $announcement_stmt->get_result();
  

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
    <main class="col-md-9 col-lg-10 py-2">
                <div class="card">
                    <div class="card-header bg-primary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fs-4">Create Announcement</h3>
                            <button class="btn btn-outline-primary btn-sm openCreateModal"
                              data-bs-toggle="modal" data-bs-target="#modalcreateannouncement">
                              + Create new
                            </button>
                        </div>
                    </div>
                </div>
    <!-- Optional: List of Previous Announcements -->
    <div class="card mt-4 border-0 shadow-sm">
      <div class="card-header bg-secondary text-white">
        <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Previous Announcements</h6>
      </div>
      <div class="card-body">
        <div class="list-group">
          <?php
            if($announcement_result->num_rows > 0 ):
              while($announcement_rowbyrow = $announcement_result->fetch_assoc()):
          ?>
          <div class="list-group-item">
            <h6 class="mb-1"><?php echo $announcement_rowbyrow['title']; ?></h6>
            <p class="mb-1 text-muted"><?php echo $announcement_rowbyrow['content']; ?></p>
            <small class="text-secondary">Posted on: <?php echo $announcement_rowbyrow['created_at']; ?></small>
            <form action="<?php  echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
              <input type="hidden" name="announcement_id" value="<?php echo $announcement_rowbyrow['announcement_id']; ?>">
              <div class="text-center">
                <button type="submit" name="delete_announcement" class="mx-auto btn btn-outline-danger">Delete</button>
              </div>
            </form>
          </div>
          <?php 
              endwhile;
            endif;
          ?>
        </div>
      </div>
    </div>
  </div>
    <!-- Modal -->
    <div class="modal fade" id="modalcreateannouncement"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" id="formcreateannouncement"  method="post">
              <div class="mb-3">
                <label for="announcementTitle" class="form-label fw-semibold">Announcement Title</label>
                <input type="text" class="form-control" id="announcementTitle" name="announcement" placeholder="Enter title here" required>
              </div>

              <div class="mb-3">
                <label for="announcementContent" class="form-label fw-semibold">Announcement Message</label>
                <textarea class="form-control" id="announcementContent" name="announcement_message" rows="5" placeholder="Write your announcement..." required></textarea>
              </div>

          
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="post_announcement" form="formcreateannouncement" class="btn btn-primary">Create Announcement</button>
          </div>
        </div>
      </div>
    </div>
</main>

  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>