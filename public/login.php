<?php include './components/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login & Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="stylesheet" href="./assets/css/login.css"/> <!-- goi login.css -->
</head>
<body>

<!-- form dang ky co id="signup" -->
<div class="container" id="signup" style="display: none;"> <!-- dat ten khối này la registerForm-->
  <h1 class="form-title">Register</h1>
  <form id="registerForm"> <!-- dat ten form la registerForm, form thì chứa trong khối-->
    <div class="input-group">
      <i class="fas fa-user"></i>
      <input type="text" name="fName" placeholder="First Name" required />
    </div>
    <div class="input-group">
      <i class="fas fa-user"></i>
      <input type="text" name="lName" placeholder="Last Name" required />
    </div>
    <div class="input-group">
      <i class="fas fa-envelope"></i>
      <input type="email" name="email" placeholder="Email" required />
    </div>
    <div class="input-group">
      <i class="fas fa-lock"></i>
      <input type="password" name="password" placeholder="Password" required />
    </div>
    <input type="submit" class="btn" value="Sign Up"/>
  </form>
  <p class="or">--------or--------</p>
  <div class="links"><p>Already have an account?</p><button id="signInButton">Sign In</button></div> <!-- nut dang dang nhap co id="signInButton" -->
</div>

<!-- form dang nhap co id="signIn" -->
<div class="container" id="signIn">
  <h1 class="form-title">Sign In</h1>
  <form id="loginForm">
    <div class="input-group">
      <i class="fas fa-envelope"></i>
      <input type="email" name="email" placeholder="Email" required />
    </div>
    <div class="input-group">
      <i class="fas fa-lock"></i>
      <input type="password" name="password" placeholder="Password" required />
    </div>
    <input type="submit" class="btn" value="Sign In"/>
  </form>
  <p class="or">--------or--------</p>
  <div class="links"><p>Don't have an account yet?</p><button id="signUpButton">Sign Up</button></div> <!-- nut dang ky co id="signUpButton" -->
</div>

<script src="assets/js/login.js"></script> <!-- goi login.js -->
</body>
</html>
