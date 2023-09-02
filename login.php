<?php
include 'functions.php';

$result = handleLoginRequest($conn);

$errorMsg = $result['errorMsg'];
$successMsg = $result['successMsg'];
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
    <link rel="stylesheet" href="css/style.css">
    <style>
    .toast {
    display: none;
    }</style>


    </head>
<body class="img js-fullheight" style="background-image: url(img/bg.jpg);">

<?php
if (isset($_SESSION['toast_message'])) {
    $message = $_SESSION['toast_message'];
    displayToast($message, 'success');
    unset($_SESSION['toast_message']); // Remove the message to prevent showing it again on page refresh
}
?>
<?php
if ($errorMsg) {
    displayToast($errorMsg, 'error');
}
if ($successMsg) {
    displayToast($successMsg, 'success');
} ?>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Welcome...</h2>
				</div>
			</div>
           
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">Have an account?</h3>
                  <form action="login.php" method="POST" class="signin-form">
		      		<div class="form-group">
		      			<input type="text" class="form-control" placeholder="Username" name="username" required>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required autocomplete="current-password">
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
                    <label class="checkbox-wrap checkbox-primary">Remember Me
    <input type="checkbox" name="remember_me" checked>
    <span class="checkmark"></span>
</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
	            </div>
	          </form>
              <p class="w-100 text-center">&mdash; Or  &mdash;</p>
<div class="d-flex justify-content-center">
    <a href="register.php" class="btn btn-secondary">Sign Up</a>
</div>
		      </div>
				</div>
			</div>
		</div>
	</section>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<?php ob_end_flush(); ?>
</body>
</html>
