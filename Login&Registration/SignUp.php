<?php
    
    session_start();
    require_once 'connect.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
        $firstname= trim($_POST['firstname'] ?? '');
        $lastname= trim($_POST['lastname'] ?? '');
        $cnic= trim($_POST['cnicnumber'] ?? '');
        $confirmcnic= trim($_POST['confirmcnic'] ?? '');
        $password= $_POST['password'] ?? '';
        $confirmpassword= $_POST['confirmpassword'] ?? '';
        $email= trim($_POST['emailnumber'] ?? '');
        $phonenumber=trim($_POST['phonenumber'] ?? '');

        $errors = [];

        // Field Validation
        $RequiredFields = [
            'firstname' => 'Full name is required',
            'cnicnumber' => 'CNIC number is required',
            'confirmcnic' => 'Matching CNIC number is required',
            'password' => 'password is required',
            'confirmpassword' => 'Matching password is required',
            'emailnumber' => 'Email number is required',
            'phonenumber' => 'Phone number is required'
        ] ;
            foreach ($RequiredFields as $fields => $message){
                if (empty(trim($_POST[$fields] ?? ''))){
                    $errors[] = $message;
                }
            }

            // Special Validations
            if($cnic !== $confirmcnic) $errors[] = "CNIC numbers do not match";
            if (!empty($password) && strlen($password) < 8) $errors[] = "password must be at least 8 characters long";
            if($password !== $confirmpassword) $errors[] = "passwrords do not match";
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
            if(!preg_match('/^[0-9]{11}$/', $phonenumber)) $errors[] = "phone number must be 11 digits long";
               
                // Checking the user already exists or not 
            
            if (empty($errors)){
                $stmt = $conn->prepare("SELECT * FROM candidate WHERE cnic = ? OR email = ?");
                $stmt->bind_param(
                    "ss",
                    $cnic,
                    $email
                );
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $errors[] = "User with this CNIC or Email already exists";
                } 
                else{ 
                    // Hashing the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $hashedconfirmpassword = password_hash($confirmpassword, PASSWORD_DEFAULT);
                    // Inserting the user Data into the database
                    $insertStmt = $conn->prepare("INSERT INTO candidate (fname, lname, cnic, email, phonnum, upassword) VALUES (?, ?, ?, ?, ?, ?)");
                    $insertStmt->bind_param(
                        "ssssss",
                        $firstname,
                        $lastname,
                        $cnic,
                        $email,
                        $phonenumber,
                        $hashedPassword, 
                    );
                    if($insertStmt->execute()){
                        // Registration Successful
                        $_SESSION['success_message'] = "Registration successful! You can now log in.";
                        header("Location: UserLogin.php");
                        exit();
                    }
                    else{
                        $errors[] = "Error occured during Registration. Please try again.";
                    }
                    $insertStmt->close(); 
                }

                $stmt->close();
            }
            
            if(!empty($errors)){
                foreach($errors as $error){
                    echo "<div class = 'alert alert-danger'> $error </div>";
                }
            }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
</head>
<body>
    <div class="container-fluid bg-primary bg-opacity-50">
        <div class="row">
            <div class="col-lg-5 ms-auto bg-white py-2">
                <div class="text-center mb-4">
                    <img src="../Picture/Sproject.png" alt="Sohail Ahmad" 
                        class="object-fit-cover rounded-circle border border-primary border-1" 
                        style="width: 90px; height: 90px;">
                        <div class="fw-semibold">To continue with testing website</div>
                        <div class="text-muted mt-2">
                            Already have account?
                            <span><a href="UserLogin.php" class="ms-2 text-decoration-none fw-semibold">User Login</a></span>
                        </div>
                </div>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" >
                            <div class="row">
                                <div class="col-md-6 my-2">
                                    <label for="SFirstName" class="form-label" >First Name</label>
                                    <input type="text" id="SFirstName" class="form-control" name="firstname" placeholder="Enter you first name" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="SLastName" class="form-label">Last Name</label>
                                    <input type="text" id="SLastName" class="form-control" name="lastname" placeholder="Enter last name">
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="SCNIC" class="form-label">CNIC</label>
                                    <input type="text" id="SCNIC" class="form-control" name="cnicnumber" placeholder="CNIC number without dashes" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="SCCNIC" class="form-label">Confrim CNIC</label>
                                    <input type="text" id="SCCNIC" class="form-control" name="confirmcnic" placeholder="Re-type CNIC number" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="Spassword" class="form-label">Password</label>
                                    <input type="password" id="Spassword" class="form-control" name="password" placeholder="Contain at least 8 character" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="SRpassword" class="form-label">Confrim Password</label>
                                    <input type="password" id="SRpassword" class="form-control" name="confirmpassword" placeholder="Re-type password" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="Semail" class="form-label">E-mail number</label>
                                    <input type="email" id="Semail" class="form-control" name="emailnumber" placeholder="enteremailnumber" required>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="Sphonenumber" class="form-label">Phone number</label>
                                    <input type="text" id="Sphonenumber" class="form-control" name="phonenumber" placeholder="Enter phone number" required>
                                </div>
                                <div class="text-center my-4">
                                    <button type="submit" class="btn btn-outline-success" name="register">Sign Up</button>
                                </div>
                            </div>
                            
                        </form>
                            
            </div>
        </div>
    </div>
    <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>