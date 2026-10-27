<?php
    session_start();
    require_once 'connect.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Alogin'])){
        // Collect and sanitize input data
        $Adminname = trim($_POST['Aname']);
        $Adminpassword = trim($_POST['Apassword']);
        if(empty($Adminname) || empty($Adminpassword)){
            echo "<script>alert('Please fill in all required fields.'); window.location.href = 'UserLogin.php'; </script>";
        }
        // Prepare and execute the SQL statement
        $sql = "SELECT id, username, Apassword FROM admin WHERE username = ?";
        $statement = $conn->prepare($sql);
        $statement -> bind_param(
            "s",
            $Adminname
        );
        $statement->execute();
        $result = $statement->get_result();
        //Check For existance
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $name = $row['username'];
            $Adpassword = $row['Apassword'];
            // Password Verification
            if(password_verify($Adminpassword, $Adpassword)){
                // Password correct, Now Creating new Session
                $_SESSION['login'] = true;
                $_SESSION['aid'] = $id;
                $_SESSION['Adminname'] = $Adname;
                // Redirect to the Admin panel
                header("location: ../AdminDashboard/Dashboard/AdminDashboard.php");
                exit();
            }
            else{
                echo "<script> alert('Invalid password.'); window.location.href = 'AdminLogin.php'; </script>";
            }
        }
        else{
            echo "<script> alert('You don not have any account. please check the name and try again.'); window.location.href = 'AdminLogin.php'; </script>";
        }
        $statement->close();
    }
    $conn->close();
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
                        <h2 class="text-primary">Admin Login</h2>
                        <div class="text-muted">Restricted access for admin only</div>
                        
                </div>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                            <div class="row">
                                <div class="col-md-12 my-2">
                                    <label for="SFirstName" class="form-label"><i class="fa-solid fa-user-shield me-1"></i>CNIC Number*</label>
                                    <input type="text" id="SFirstName" class="form-control" placeholder="Enter admin user name" name="Aname" required>
                                    
                                </div>
                                <div class="col-md-12 my-2">
                                    <label for="SLastName" class="form-label"><i class="fa-solid fa-lock me-2"></i>Password*</label>
                                    <input type="password" id="SLastName" class="form-control" placeholder="Enter password" name="Apassword" required>
                                    
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-success" name="Alogin">Log in</button>
                                </div>
                            </div>
                            
                        </form>
                            

                    

                

            </div>
        </div>
    </div>
</body>
</html>