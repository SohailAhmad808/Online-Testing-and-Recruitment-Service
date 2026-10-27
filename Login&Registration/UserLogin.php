<?php
    session_start();
    require_once 'connect.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
        // Collect and sanitize input data
        $UCNIC = trim($_POST['UCNIC']);
        $Upassword = trim($_POST['Upassword']);
        if(empty($UCNIC) || empty($Upassword)){
            echo "<script>alert('Please fill in all required fields.'); window.location.href = 'UserLogin.php'; </script>";
        }
        // Prepare and execute the SQL statement
        $sql = "SELECT id, cnic, upassword FROM candidate WHERE cnic = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param(
            "s",
            $UCNIC
        );
        $stmt -> execute();
        $result = $stmt -> get_result();
        // Check For User existence
        if($result ->num_rows == 1){
            $row = $result -> fetch_assoc();
            $id = $row['id'];
            $cnic = $row['cnic'];
            $hashed_password = $row['upassword'];
            // Verify password
            if(password_verify($Upassword, $hashed_password)){
                // Password is correct, start a new session
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['cnic'] = $cnic;
                // Redirect to welcome page
                header("location: ../UserDashboard/dashboard/UserDashboard.php");
                exit();
            }
            else{
                echo "<script> alert('Invalid password.'); window.location.href = 'UserLogin.php'; </script>";
            }
        }
        else{
            echo "<script> alert('No account found with that CNIC.'); window.location.href = 'UserLogin.php'; </script>";
        }
        $stmt -> close();

    }
    $conn -> close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
     <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container-fluid bg-primary bg-opacity-50 vh-100">
        <div class="row">
            <div class="col-lg-5 bg-white vh-100 p-3 ms-auto">
                <div class="text-center mb-4">
                    <img src="../Picture/Sproject.png" alt="Sohail Ahmad" 
                        class="object-fit-cover rounded-circle border border-primary border-1" 
                        style="width: 90px; height: 90px;">
                        <div class="fw-semibold">Sign in to you account</div>
                        
                </div>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                            <div class="row">
                                <div class="col-md-12 my-2">
                                    <label for="ucnic" class="form-label" required><i class="fa-solid fa-id-card me-2"></i>CNIC Number*</label>
                                    <input type="text" id="ucnic" class="form-control" placeholder="Enter CNIC number" name="UCNIC">
                                    
                                </div>
                                <div class="col-md-12 my-2">
                                    <label for="SLastName" class="form-label"><i class="fa-solid fa-lock me-2"></i>Password*</label>
                                    <input type="password" id="SLastName" class="form-control" placeholder="Enter password" name="Upassword">
                                    
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-success" name="login">Log in</button>
                                    <div class="text-muted mt-2">
                                        Don't have any account
                                        <span><a href="SignUp.php" class="ms-2 text-decoration-none fw-semibold">SignUP</a></span>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
            </div>
        </div>
    </div>
</body>
</html>