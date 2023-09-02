<?php
include 'db.php';
include 'functions.php';
session_start();
$errorMsg = '';
$successMsg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        if ($existingUser['username'] == $username) {
            $errorMsg = "Username already exists!";
        } else if ($existingUser['email'] == $email) {
            $errorMsg = "Email already registered!";
        }
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, email, date_registered) VALUES (?, ?, ?, NOW())");
        
        if ($stmt->execute([$username, $hashedPassword, $email])) {
            $successMsg = "Registration successful! You can now login.";
            header("Location: login.php?success=" . urlencode($successMsg)); 
            exit;
        } else {
            $errorMsg = "There was an error with the registration. Please try again.";
        }
    }
}


if ($errorMsg) {
    displayToast($errorMsg, 'error');
}
if ($successMsg) {
    displayToast($successMsg, 'success');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
    .toast {
    display: none;
    }</style>


    </head>
<body>
<body class="img js-fullheight" style="background-image: url(img/bg.jpg);">

<div class="toast position-absolute top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="15000">
    <div class="toast-header bg-danger text-white">
        <strong class="me-auto">Error</strong>
        <small class="text-muted">Just now</small>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        Registration failed.
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Registration</h2>
            </div>
        </div>
       
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-wrap p-0">
                    <h3 class="mb-4 text-center">Create an account</h3>
                    <form action="register.php" method="POST" class="signin-form">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Username" name="username" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                        </div>
                        <div class="form-group">
                            <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary submit px-3">Register</button>
                        </div>
                    </form>
                    <p class="w-100 text-center mt-4">
                <a href="login.php" style="color: #fff">Return to login &rarr;</a>
            </p>
                </div>
                
            </div>
        </div>
    </div>
</section>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
