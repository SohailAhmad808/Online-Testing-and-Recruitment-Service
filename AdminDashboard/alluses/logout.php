
<?php
session_start();

// Unset only the specific admin session variables
unset($_SESSION['login']);
unset($_SESSION['aid']);
unset($_SESSION['Adminname']);
// Redirect to admin login page
header("Location: ../../Login&Registration/AdminLogin.php");
exit();
?>



    <!--
    <main class="col-md-9 col-lg-10">
        <div class="row g-4">

             Account Security 
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

    <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
    </main>
-->