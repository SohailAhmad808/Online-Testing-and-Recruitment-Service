<?php
  require_once '../connect/connect.php';


  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])){
    if (!empty($_FILES['profile_image']['name'])){
      $imageName = $_FILES['profile_image']['name'];
      $imageTmp  = $_FILES['profile_image']['tmp_name'];
      // Getting the extention of the file
      $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

      if (!in_array($imageExt, $allowedExt)){
        $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
      }
      else{
        // Create uploads folder if needed
        if (!is_dir('uploads')){
          mkdir('uploads', 0777, true);
        }
        // Create unique file name
        $newName = "CAND_" . $user_id . "_" . time() . "." . $imageExt;
        $uploadPath = "../uploads/" . $newName;
        if (move_uploaded_file($imageTmp, $uploadPath)){
          // Get old active image
          $oldImgQuery = $conn->prepare("SELECT profile_image FROM candidate WHERE id = ?");
          $oldImgQuery->bind_param(
            "i",
            $user_id
          );
          $oldImgQuery->execute();
          $oldImgResult = $oldImgQuery->get_result();
          if ($oldImgResult->num_rows > 0){
            $oldImage = $oldImgResult->fetch_assoc()['profile_image'];
            // Delete old image file
            if (!empty($oldImage)){
              $oldImagePath = "../uploads/" . $oldImage;
              if(file_exists($oldImagePath))
                unlink($oldImagePath);
            }
          }
          // Insert new image
          $stmt = $conn->prepare("UPDATE candidate SET profile_image = ? WHERE id = ?");
          $stmt->bind_param("si", $newName, $user_id);
          if ($stmt->execute()) {
            header("Location: UserProfileimage.php?success=1");
            exit;
          } else {
              $error = "Database error while saving image.";
          }
        }else{
          $error = "Please select an image to upload.";
        }
      }
    }
  }
  // Fetch user's current profile image
  $user_sql = "SELECT profile_image FROM candidate WHERE id = ?";
  $userstmt = $conn->prepare($user_sql);
  $userstmt->bind_param(
    "i",
    $user_id
  );
  $userstmt->execute();
  $userResult = $userstmt->get_result();

  $currentImage = "";
  if ($userResult->num_rows > 0) {
      $currentImage = $userResult->fetch_assoc()['profile_image'];
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
      Convas Start Here
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">

  <div class="container py-4">
      <div class="row justify-content-center">
          <div class="col-md-8">

              <div class="card shadow-sm p-4 text-center">

                  <h4 class="mb-3">Upload Profile Image</h4>

                  <!-- Profile Image Placeholder -->
                  <div class="mb-5">
                      <img id="previewImage" 
                           src="
                            <?php
                              if(!empty($currentImage)){
                                echo '../uploads/' . $currentImage;
                              }
                              else{

                              }
                             ?>
                           "
                           class="rounded-circle border"
                           style="width: 200px; height:200px; object-fit: cover;">
                  </div>

                  <!-- Choose File Button -->
                  <form method="POST" enctype="multipart/form-data">
                      <div class="mb-3">
                          <label class="form-label fw-bold">Select Profile Image</label>
                          <input type="file" name="profile_image" class="form-control" accept="image/*" onchange="previewFile(event)">
                      </div>

                      <button type="submit" name="upload" class="btn btn-primary w-100">
                          <i class="fa-solid fa-upload"></i> Upload Image
                      </button>
                  </form>

              </div>

          </div>
      </div>
  </div>

</main>

<!-- Preview Script -->
<script>
    function previewFile(event) {
        let output = document.getElementById('previewImage');
        output.src = URL.createObjectURL(event.target.files[0]);
    }
</script>

  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>