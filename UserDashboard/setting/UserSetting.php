<?php
    require_once '../connect/connect.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatepassword'])){
        $current = trim($_POST['cpassword']) ?? '';
        $new = trim($_POST['npassword']) ?? '';
        $confirm = trim($_POST['cnpassword']) ?? '';

        // Check empty fields
        if(empty($current) || empty($new) || empty($confirm)){
            echo "<script>alert('All fields are required.');</script>";
            exit;
        }

        // Check new password match
        if($new !== $confirm){
            echo "<script>alert('New passwords do not match!');</script>";
            exit;
        }
        // Fetch stored hash
        $sql = "SELECT upassword FROM candidate WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $user_id
        );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $hashedPassword = $result['upassword'];

        // Verify current password
        if(!password_verify($current, $hashedPassword)){
            echo "<script>alert('Current password is incorrect!');</script>";
            exit;
        }
        // Hash new password
        $newHashed = password_hash($new, PASSWORD_BCRYPT);
        // Update password
        $updateSql = "UPDATE candidate SET upassword = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param(
            "si", 
            $newHashed, 
            $user_id
        );

        $updateStmt->execute();

        if($updateStmt->affected_rows > 0){
            echo "<script>alert('Password updated successfully!');</script>";
        } else {
            echo "<script>alert('Failed to update password!');</script>";
        }

        $stmt->close();
        $updateStmt->close();
        $conn->close();
        
    }
    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Settings - OTRS</title>
  <link rel="icon" type="image/x-icon" href="Sproject.png">

  <!-- Bootstrap & Icons -->
  <link href="../../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css" rel="stylesheet">
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
        <!-- Content -->

            <div class="row g-4">

            <!-- Account Security -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Account Security</div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                        <div class="mb-3">
                            <label class="form-label" for="currentpassword">Current Password</label>
                            <input type="password" class="form-control" id="currentpassword" placeholder="Enter current password" name="cpassword" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="newpassword">New Password</label>
                            <input type="password" class="form-control" id="newpassword" placeholder="Enter new password" name="npassword" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cnewpassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="cnewpassword" placeholder="Confirm new password" name="cnpassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="updatepassword">Update Password</button>
                    </form>
                    <hr>
                    
                </div>
                </div>
            </div>

           
            </div>
        
    </main>

  <!-- Bootstrap JS -->
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
