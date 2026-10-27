<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "fyp";

    $conn = mysqli_connect($servername, $username, $password, $databasename);
    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }
    // Check if user is logged in
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
      header("location: ../../Login&Registration/UserLogin.php");
      exit();
    }
    // Session variables
    $user_id = $_SESSION['id'];
    $user_cnic = $_SESSION['cnic'];
    $firstname = '';
    $sqle = "SELECT fname FROM candidate WHERE id=?";
    $mystatement = $conn->prepare($sqle);
    $mystatement -> bind_param(
      "i",
      $user_id
    );
    $mystatement -> execute();
    $HeadResult = $mystatement -> get_result();
    if($HeadResult-> num_rows == 1){
      $row = $HeadResult-> fetch_assoc();
      $firstname = $row['fname']; 
    }
    $mystatement -> close();

    
    
    
?>