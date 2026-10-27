
<?php
session_start();

// Unset only the specific session variables used in user login
unset($_SESSION['loggedin']);
unset($_SESSION['id']);
unset($_SESSION['cnic']);
// Redirect to user login page
header("Location: ../../Login&Registration/UserLogin.php");
exit();
?>