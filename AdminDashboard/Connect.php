<?php
    // session start
    session_start();

    //Connect with database
    $servername="localhost";
    $username="root";
    $password="";
    $databasename="FYP";
    $conn = mysqli_connect($servername, $username, $password, $databasename);
    if(!$conn){
        die("Connection Failed: ". mysqli_connect_error());
    }
    
    // Check if user is login or not 
    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
      header("location: ../../Login&Registration/AdminLogin.php");
      exit();
    }

    // Session variables
    $admin_id = $_SESSION['aid'];
    $admin_name = $_SESSION['Adminname'];
    
?>