<?php
  require_once '../Connect.php';

    // Deleting the one education row form the table
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["delete_post"])){
    
    // Converting into safe integer
    $delete_id = intval($_POST['delete_id']);

    // Deletiing query
    $deleteSql = "DELETE FROM posts WHERE post_id = ?";
    $delStmt = $conn->prepare($deleteSql);
    $delStmt->bind_param(
      "i",
      $delete_id
    );

    if ($delStmt->execute()) {
        header("Location: ManagePost.php?deleted=1");
        exit();
    } else {
        error_log($delStmt->error);
        header("Location: ManagePost.php?error=delete_failed");
        exit();
    }

  }
  // When Update form was submmitted
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_post'])){
    // Initialize the values
    
    $categery = trim($_POST['categery']) ?? '';
    $post_description = trim($_POST['post_description']) ?? '';
    $deadline = trim($_POST['deadline']) ?? '';
    $post_id = $_POST['post_id'];
    // Input validity
    if ($categery === '' || $post_description === '' || $deadline === '') {
          header("Location: ManagePost.php?error=empty_fields");
          exit();
      }
    $sql ="UPDATE posts SET 
    categery = ?, 
    post_description = ?, 
    deadline = ?

    WHERE
    post_id=? ";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param(
      "ssss",
      $categery,
      $post_description,
      $deadline,
      $post_id
    );
    if ($stmt->execute()) {
        header("Location: ManagePost.php?success=1");
        exit();
    } else {
      
        error_log($stmt->error);
        header("Location: ManagePost.php?error=db_error");
        exit();
    }

  }
  // When create post button was submmitted
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createpost'])){
    // Initialize the values
    $title = trim($_POST['title']) ?? '';
    $categery = trim($_POST['categery']) ?? '';
    $post_description = trim($_POST['post_description']) ?? '';
    $deadline = trim($_POST['deadline']) ?? '';
    $typ = trim($_POST['typ']) ?? '';
    // Input validity
    if ($title === '' || $categery === '' || $post_description === '' || $deadline === '' || $typ === '') {
          header("Location: ManagePost.php?error=empty_fields");
          exit();
      }
    $sql ="INSERT INTO posts (title, categery, post_description, deadline, typ) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param(
      "sssss",
      $title,
      $categery,
      $post_description,
      $deadline,
      $typ
    );
    if ($stmt->execute()) {
        header("Location: ManagePost.php?success=1");
        exit();
    } else {
      
        error_log($stmt->error);
        header("Location: ManagePost.php?error=db_error");
        exit();
    }

  }
  // Fetching data from the table
  $sql = "SELECT * FROM posts ORDER BY post_id DESC";
  $statment = $conn-> prepare($sql);
  $statment -> execute();
  $result = $statment->get_result();
  $statment->close();


  $post_data = null;
  $modal_show = false;
  if (isset($_GET['view_profile'])){
    $modal_show = true;
    
    // geting the data of student to show insight
    $post_id = intval($_GET['view_profile']);

    //Getting profile information
    $post_update_sql = "SELECT * FROM posts WHERE post_id = ?";
    $post_update_stmt = $conn->prepare($post_update_sql);
    $post_update_stmt->bind_param("i", $post_id);
    $post_update_stmt->execute();
    $post_result = $post_update_stmt->get_result();
    $post_data = $post_result->fetch_assoc();
    
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
    <main class="col-lg-10">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="fw-bold fs-4">Post Management</div>
                    <button class="btn btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#CreatePost">
                        + Create Post
                    </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Advertised Post</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered rounded-2">
                            <caption>Here's active posts</caption>
                            <thead class="table-light">
                                <tr>
                                
                                <th scope="col">Title</th>
                                <th scope="col">Type</th>
                                <th scope="col">category</th>
                                <th scope="col">Description</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Update/Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  if ($result && $result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()){

                                ?>
                                <tr>
                                  <td><?php echo htmlspecialchars($row['title']); ?></td>
                                  <td><?php echo htmlspecialchars($row['typ']); ?></td>
                                  
                                  <td><?php echo htmlspecialchars($row['categery']); ?></td>
                                  <td><?php echo htmlspecialchars($row['post_description']); ?></td>
                                  <td><?php echo htmlspecialchars($row['deadline']); ?></td>
                                  <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                      
                                      <a href="?view_profile=<?php echo $row['post_id']; ?>" class="btn btn-primary"><i class="fas fa-edit " style="cursor:pointer;"></i></a>
                                      
                                      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                          <input type="hidden" name="delete_id" value="<?php echo $row['post_id']; ?>">
                                          <button type="submit" name="delete_post" class="btn btn-danger"><i class="fas fa-trash " style="cursor:pointer;"></i></button>
                                      </form>
                                    </div>
                                  </td>
                                </tr>
                                <?php
                                      }
                                  }
                                  ?>
                            </tbody>
                        </table>
                    </div>

                  <!-- Modal -->
                  <div class="modal fade" id="CreatePost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title text-primary fw-bold lead" id="staticBackdropLabel">My Education</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" id="postform" method="post">
                            <div class="row g-3">
                                
                              <div class="col-md-6">
                                <label for="title">Title</label>
                                <input type="text" id="title" class="form-control" name="title" required>
                              </div>
                              
                              <div class="col-md-6">
                                <label for="categery">Catagery</label>
                                <input type="text" id="categery" class="form-control" name="categery" required>
                              </div>
                              <div class="mb-6">
                                    <label for="post_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="post_description" name="post_description" rows="3"></textarea>
                                </div>
                              <div class="col-md-6">
                                <label for="deadline">End Date</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                              </div>
                              <div class="col-md-6">
                                <label for="typ">Test Type</label>
                                <select id="typ" class="form-control" name="typ" required>
                                    <option value="">Select Test Type</option>
                                    <option value="cbt">Computer Based Test (CBT)</option>
                                    <option value="pbt">Physical Based Test (PBS)</option>
                                </select>
                            </div>
                            </div>
                          </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-outline-success" form="postform" name="createpost">Save Information</button>
                        </div>
                      </div>
                    </div>
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
                        <?php if(isset($post_data)): ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" id="updateform" method="post">
                            <div class="row g-3">
                                
                              <div class="col-md-6">
                                <label for="title">Title</label>
                                <input type="text" id="title" class="form-control" name="title" value="<?php echo $post_data['title']  ?>" disabled required>
                              </div>
                              
                              <div class="col-md-6">
                                <label for="categery">Catagery</label>
                                <input type="text" id="categery" class="form-control" name="categery" value="<?php echo $post_data['categery']  ?>" required>
                              </div>
                              <div class="mb-6">
                                    <label for="post_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="post_description" name="post_description" rows="3"><?php echo htmlspecialchars($post_data['post_description']); ?></textarea>
                                </div>
                              <div class="col-md-6"> 
                                <label for="deadline">End Date</label>
                                <input type="date" class="form-control" id="deadline" value="<?php echo $post_data['deadline']  ?>" name="deadline" required>
                              </div>
                              <div class="col-md-6"> 
                                <label for="Whichtype">Type</label>
                                <input type="text" class="form-control" id="Whichtype" value="<?php echo $post_data['typ']  ?>" disabled name="type" required>
                              </div>
                              <input type="hidden" id="post_id" name="post_id" value="<?php echo $post_data['post_id']; ?>">
                            </div>
                          </form>
                          <?php endif; ?>
                            
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="updateform" name="update_post">>Update Post</button>
                      </div>
                    </div>
                  </div>
                </div>
    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  <?php if($modal_show && $post_data): ?>
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





