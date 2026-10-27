<?php
    require_once '../connect/connect.php';
    $user_id = $_SESSION['id'];
    $user_cnic = $_SESSION['cnic'];
    // Initialize variables for user data
    $firstname = '';
    $lastname = '';
    $cnicnumber = '';
    $fathername = ''; 
    $gender = '';
    $dob = '';
    $religion = '';
    $email = '';
    $mobileno = '';
    $whatsappno = '';
    $address = '';
    $maritalstatus = '';
    $hafizquran = '';
    // Fetch user data from database
    $sql = "SELECT fname, lname, cnic, fathername, gender, dob, religion, email, phonnum, whatsappnum, uaddress, maritalstatus, hafizquran FROM candidate WHERE id = ?";
    $statement = $conn-> prepare($sql);
    $statement-> bind_param("i", $user_id);
    $statement-> execute();
    $result = $statement-> get_result();

    if($result-> num_rows == 1){
        $row = $result-> fetch_assoc();
        $firstname = $row['fname'];
        $lastname = $row['lname'];
        $cnicnumber = $row['cnic'];
        $fathername = $row['fathername'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $religion = $row['religion'];
        $email = $row['email'];
        $mobileno = $row['phonnum'];
        $whatsappno = $row['whatsappnum'];
        $address = $row['uaddress'];
        $maritalstatus = $row['maritalstatus'];
        $hafizquran = $row['hafizquran'];

    }
    $statement-> close();
    // Handle form submission for updating user profile
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Collect and sanitize input data
        $fathername = trim($_POST['FatherName'] ?? '');
        $gender =trim($_POST['Gender'] ?? '');
        $dob = trim($_POST['DateOfBirth'] ?? '');
        $religion = trim($_POST['Religion'] ?? '');
        $email = trim($_POST['Email'] ?? '');
        $mobileno = trim($_POST['MobileNo'] ?? '');
        $whatsappno = trim($_POST['whatsappno'] ?? '');
        $address = trim($_POST['Address'] ?? '');
        $maritalstatus = trim($_POST['MaritalStatus'] ?? '');
        $hafizquran = trim($_POST['HafizQuran'] ?? '');
        // Validate required fields 
        $errors = [];
        // Required fields validation
        if(empty($fathername) || empty($gender) || empty($dob) || empty($email) || empty($mobileno) || empty($hafizquran)){
            $errors [] = "Please fill in all required fields marked with *";
        }
        // Special Validation
        if(empty($errors)){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
            if(!preg_match('/^[0-9]{11}$/', $mobileno)) $errors[] = "phone number must be 11 digits long";
        }
        // Checking the email already exist or not
        if(empty($errors)){
            $sql2 = "SELECT id FROM candidate WHERE email = ? AND id != ?";
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param(
                "si",
                $email,
                $user_id
            );
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $errors[] = "Email already exists";
            } 
            $stmt-> close();
        }
        // If no errors, Update the user data
        if(empty($errors)){
            $updateSql = "UPDATE candidate SET 
            fathername = ?,
            gender = ?,
            dob = ?,
            religion = ?,
            email = ?,
            phonnum = ?,
            whatsappnum = ?,
            uaddress = ?,
            maritalstatus = ?,
            hafizquran = ?
            WHERE id = ?";

            $updatestatement = $conn -> prepare($updateSql);
            $updatestatement -> bind_param(
                "ssssssssssi",
                $fathername,
                $gender,
                $dob,
                $religion,
                $email,
                $mobileno,
                $whatsappno,
                $address,
                $maritalstatus,
                $hafizquran,
                $user_id
            );
            if($updatestatement -> execute()){
                echo "<script>alert('Profile updated successfully.'); window.location.href = 'UserProfile.php'; </script>";
            }
            else{
                echo "<script>alert('Error updating profile. Please try again.'); window.location.href = 'UserProfile.php'; </script>";
            }
            $updatestatement -> close();            
        }
        if(!empty($errors)){
            foreach($errors as $error){
                echo "<script>alert('".$error."'); window.location.href = 'UserProfile.php'; </script>";
            }
        }


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
    <!--      User Header bar      -->
    <?php
      require '../others/UserHeader.php';
    ?>
     <!--      User Sidebar    -->
    <?php
      require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">
        <!--
            The Cards for Profile Starts Here 
        -->
        <div class="card shadow-lg mx-lg-3 my-lg-2">
            <div class="card-header bg-primary-subtle">
                <h3 class="p-1 text-center"><i class="fa-solid fa-user me-2 me-3"></i>USER PROFILE</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" class="p-1 needs-validation" method="POST">

                    <h6 class="text-primary"><i class="fa-solid fa-id-card me-2"></i>Personal Details</h6>

                    <div class="row g-2 pt-2 mb-3">
                        <div class="col-md-6">
                            <label for="FirstName" class="form-label"><span class="text-danger fw-bold">*</span>First Name</label>
                            <input type="text" class="form-control" id="FirstName" placeholder="ABC" value="<?php echo htmlspecialchars($firstname) ?>" name="fname" disabled required>
                        </div>

                        <div class="col-md-6">
                            <label for="LastName" class="form-label"><span class="text-danger fw-bold">*</span>Last Name</label>
                            <input type="text" class="form-control" id="LastName" placeholder="Enter the Last Name" value="<?php echo htmlspecialchars($lastname); ?>" name="lname" disabled required>
                        </div>

                        <div class="col-md-6">
                            <label for="fname" class="form-label"><span class="text-danger fw-bold">*</span>Father's Name</label>
                            <input type="text" class="form-control" id="fname" placeholder="Enter father name" value="<?php echo htmlspecialchars($fathername); ?>" name="FatherName" required>
                        </div>

                        <div class="col-md-6">
                            <label for="FormSelect" class="form-label"><span class="text-danger fw-bold">*</span>Gender</label>
                            <select class="form-select" id="FormSelect" aria-label="Form Select" name="Gender" required>
                                <option selected disabled>--Select Gender--</option>
                                <option value="Male"<?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female"<?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other"<?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label"><span class="text-danger fw-bold">*</span>Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="DateOfBirth" value="<?php echo htmlspecialchars($dob) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="CNIC" class="form-label"><span class="text-danger fw-bold">*</span>CNIC number</label>
                            <input type="text" class="form-control" id="FatherName" value="<?php echo htmlspecialchars($cnicnumber) ?>" name="cnic" disabled required>
                        </div>

                        <div class="col-md-6">
                            <label for="religion" class="form-label">Religion</label>
                            <select class="form-select" id="religion" aria-label="Form Select" name="Religion">
                                <option selected disabled>--Select Religion--</option>
                                <option value="Muslim" <?php if($religion == 'Muslim') echo 'selected'; ?>>Muslim</option>
                                <option value="Non-muslim" <?php if($religion == 'Non-muslim') echo 'selected'; ?>>Non-muslim</option>
                            </select>
                        </div>

                    </div>

                  <h6 class="text-primary"><i class="fa-solid fa-address-book me-2"></i>Contact Information</h6>

                  <div class="row g-2 pt-2 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label"><span class="text-danger fw-bold">*</span>Email Address</label>
                        <input type="text" class="form-control" id="email" placeholder="example@gmail.com" name="Email" value="<?php echo htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="mobileno" class="form-label"><span class="text-danger fw-bold">*</span>Mobile Number</label>
                        <input type="text" class="form-control" id="mobileno" placeholder="03XX-XXXXXXX" name="MobileNo" value="<?php echo htmlspecialchars($mobileno) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="WhatsAppNo" class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" id="WhatsAppNo" placeholder="03XX-XXXXXXX" name="whatsappno" value="<?php echo htmlspecialchars($whatsappno) ?>">
                    </div>

                    <div class="col-md-6">
                      <label for="address" class="form-label">Address</label>
                      <input type="text" class="form-control" id="address" placeholder="Enter you Address" name="Address" value="<?php echo htmlspecialchars($address) ?>">
                    </div>
                  </div>
                  
                  <h6 class="text-primary"><i class="fa-solid fa-circle-info me-2"></i>Other Details</h6>

                  <div class="row g-2 pt-2 mb-3">

                    <div class="col-md-6">
                        <label for="Mstatus" class="form-label">Marital Status</label>
                        <select class="form-select" id="Mstatus" aria-label="Form Select" name="MaritalStatus">
                            <option selected disabled>--Select Status--</option>
                            <option value="Single" <?php if($maritalstatus == 'Single') echo 'selected'; ?>>Single</option>
                            <option value="Married" <?php if($maritalstatus == 'Married') echo 'selected'; ?>>Married</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="HQuran" class="form-label"><span class="text-danger fw-bold">*</span>Hafiz Quran</label>
                        <select class="form-select" id="HQuran" aria-label="Form Select" name="HafizQuran" required>
                            <option selected disabled>--Select Status--</option>
                            <option value="No" <?php if($hafizquran == 'No') echo 'selected'; ?>>No</option>
                            <option value="Yes" <?php if($hafizquran == 'Yes') echo 'selected'; ?>>Yes</option>
                        </select>
                    </div>

                  </div>
                  <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-outline-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i>Save Information</button>
                  </div>
                </form>
            </div>

        </div>
        <!--
            Card End Here
        -->
    </main>
  </div>
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  
  </body>
  <?php
    $conn->close();
  ?>
</html>