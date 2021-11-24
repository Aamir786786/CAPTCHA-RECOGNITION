<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '','mini_project');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $Name = mysqli_real_escape_string($db, $_POST['Name']);
  $org_name = mysqli_real_escape_string($db, $_POST['org_name']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $conf_password = mysqli_real_escape_string($db, $_POST['conf_password']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) {
    array_push($errors, "Username is required"); 
  }
  if (empty($Name)) {
    array_push($errors, "Name is required");
  }
  //if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password)) { 
    array_push($errors, "Password is required");
  }
  if ($password != $conf_password) {
	  array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM registration WHERE username='$username'  LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
      echo "<script><alert>Username already exists </alert><script>";
      header('location: registeration_page.html');
    }
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
    
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password);//encrypt the password before saving in the database

  	$query = "INSERT INTO registration (username, Name, email, Org_name,  password, conf_password) 
  			  VALUES('$username','$Name', '$email','$org_name', '$password', '$conf_password')";
  	$results1=mysqli_query($db, $query);
  	
    header('location: login_page.html');
  }
    
}
?>