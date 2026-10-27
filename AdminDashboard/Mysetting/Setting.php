<?php
  require_once '../Connect.php';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatepassword'])){
        $current = trim($_POST['cpassword']) ?? '';
        $new = trim($_POST['npassword']) ?? '';
        $confirm = trim($_POST['cnpassword']) ?? '';

        $adminID = $_SESSION['aid'];

        // Check empty fields
        if(empty($current) || empty($new) || empty($confirm)){
            echo "<script>alert('All fields are required.'); window.location.href='QuestionPool.php';</script>";
            exit;
        }

        // Check new password match
        if($new !== $confirm){
            echo "<script>alert('New passwords do not match!'); window.location.href='Setting.php';</script>";
            exit;
        }
        // Fetch stored hash
        $sql = "SELECT Apassword FROM admin WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $adminID
        );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $hashedPassword = $result['Apassword'];

        // Verify current password
        if(!password_verify($current, $hashedPassword)){
            echo "<script>alert('Current password is incorrect! window.location.href='Setting.php';</script>";
            exit;
        }
        // Hash new password
        $newHashed = password_hash($new, PASSWORD_BCRYPT);
        // Update password
        $updateSql = "UPDATE admin SET Apassword = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param(
            "si", 
            $newHashed, 
            $adminID
        );

        $updateStmt->execute();

        if($updateStmt->affected_rows > 0){
            echo "<script>alert('Password Update sucessfully'); window.location.href='Setting.php';</script>";
        } else {
            echo "<script>alert('Failed to update password!'); window.location.href='Setting.php';</script>";
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
        <div class="row my-4">
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

    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>