<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMagic | Where Every Frame Tells A Story</title>
	<link rel="website icon" type="JPG" href="C:\xampp\htdocs\MovieMagic\Images and Videos\Icon.jpeg">
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
		*{
			margin:0;
			padding:0;
			box-sizing: border-box;
			font-family: 'Poppins',sans-serif;
		}
		body{
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			background: url("ImagesandVideos/Backgroud_Images/BG7.jpg") no-repeat;
			background-size: cover;
			background-position: center;

		}
		header{
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			padding: 20px 100px;
			margin-top: 20px;
			height: 70px;
			display: flex;
			background: transparent;
			background-color: rgba(59, 57, 57, 0.5);
			justify-content: space-between;
			align-items: center;
			z-index: 99;
		}
		.logo{
			font-size: 2em;
			color: #01939c;
			user-select: none;
		}
		.navigation a{
			position: relative;
			font-size: 1.1rem;
			color: #3cb2e9;
			text-decoration: none;
			font-weight: 500;
			margin-left: 40px;
		}
		.navigation .btnLogin-popup{
			width: 90px;
			height: 50px;
			background: transparent;
			border: .5px solid #3cb2e9;
			outline: none;
			border-radius: 40px;
			cursor: pointer;
			font-size: 1.1em;
			color: #3cb2e9;
			font-weight: 500;
			margin-left: 40px;
			transition: .5s;
		}
		.navigation a::after{
			content: '';
			position: absolute;
			left: 0;
			bottom: -6px;
			width: 100%;
			height: 3px;
			background: #3cb2e9;
			border-radius: 5px;
			transform: scaleX(0);
			transform: transform .5s;
		}
		.navigation a:hover:after{
			transform: scaleX(1);
		}
		.navigation .btnLogin-popup:hover{
			background: #3cb2e9;
			color: #162938;
		}
		.wrapper{
			position: relative;
			margin-top: 60px;
			width: 400px;
			height: 440px;
			background: transparent;
			background-color: rgba(0, 0, 0, .5);
			backdrop-filter: blur(20px);
			border: none;
			border-radius: 20px;
			box-shadow: 0 0 30px rgba(0, 0, 0, .5);
			display: flex;
			justify-content: center;
			align-items: center;
			overflow: hidden;
			transform: scale(0);
			transition: transform .5s ease, height .2s ease;
		}
		.wrapper.active-popup{
			transform: scale(1);
		}
		.wrapper.active{
			height: 500px;
		}
		.wrapper .form-box{
			width: 100%;
			padding: 40px;
		}
		.wrapper .form-box.login{
			transition: transform .18s ease;
			transform: translateX(0);
		}
		.wrapper.active .form-box.login{
			transition: none;
			transform: translateX(-400px);
		}
		.wrapper .form-box.register{
			position: absolute;
			transition: none;
			transform: translateX(400px);
		}
		.wrapper.active .form-box.register{
			transition: transform .18s ease;
			transform: translateX(0);
		}
		.wrapper .icon-close{
			position: absolute;
			top: 0;
			right: 0;
			width: 45px;
			height: 45px;
			background: #3cb2e9;
			font-size: 2em;
			color: #d8d2d2;
			display: flex;
			justify-content: center;
			align-items: center;
			border-bottom-left-radius: 20px;
			cursor: pointer;
			z-index: 1;
		}
        .form-box {
			position: relative;
			width: 100%;
			height: 100%;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			padding: 0 2rem;
			overflow: visible;
		}
		.form-box h2{
			font-size: 2em;
			color: #fff;
			text-align: center;
            margin-bottom: 5px;
		}
		.input-box{
			position: relative;
			width: 100%;
			height: 65px;
			border-bottom: 2px solid #d8d2d2;
			margin-bottom: 1rem;
			margin-top: -5px;
		}
		.input-box label{
			position: absolute;
			top: 50%;
			left: 15px;
			transform: translateY(-50%);
			font-size: 1em;
			color: #d8d2d2;
			font-weight: 500;
			pointer-events: none;
			transition: .5s;
            overflow: hidden;
			height: 45px;
		}
		.input-box input:focus~label, .input-box input:valid~label{
			top: -5px;
            height: 0;
 		 	overflow: visible;
		}
		.input-box input{
			width: 100%;
			height: 65px;
			background: transparent;
			border: none;
			outline: none;
			font-size: 1.1em;
			color: #3cb2e9;
			font-weight: 500;
			padding: 10px 15px 0;
		}
		.input-box .icon{
			position: absolute;
			right: 8px;
			font-size: 1.2em;
			color: #d8d2d2;
			line-height: 57px;
		}
		.remember-forgot{
			font-size: .9em;
			color: #3cb2e9;
			font-weight: 500;
			margin: -15px 0 15px;
			display: flex;
			justify-content: space-between;
		}
		.remember-forgot label input{
			accent-color: #3cb2e9;
			margin-right: 3px;
		}
		.remember-forgot a{
			color: #3cb2e9;
			text-decoration: none;
		}
		.remember-forgot a:hover{
			text-decoration: underline;
		}
		.btn{
			width: 100%;
			height: 45px;
			background: #3cb2e9;
			border: none;
			outline: none;
			border-radius: 6px;
			cursor: pointer;
			font-size: 1em;
			color: #0a0a0a;
			font-weight: 500;
            font-size: 1.1em;
			margin-bottom: 1rem;
		}
		.signin-register{
			font-size: .9em;
			color: #d8d2d2;
			text-align: center;
			font-weight: 500;
			
            margin-bottom: 1rem;
            display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
		}
        .signin-register p {
			width: 100%;
		}
		.signin-register p a{
			color: #3cb2e9;
			text-decoration: none;
			font-weight: 600;
		}
		.signin-register p a:hover{
			text-decoration: underline;
		}
	</style>
</head>
<body>
    <header>
		<h2 class="logo">MovieMagic</h2>
		<nav class="navigation">
			<a href="HomePage.php">Home</a>
			<a href="aboutus.php">About</a>
			<a href="services.php">Services</a>
			<a href="contact.php">Contact</a>
			<button class="btnLogin-popup">Sign In</button>
		</nav>
	</header>
	<div class="wrapper">
		<span class="icon-close">
			<ion-icon name="close"></ion-icon>
		</span>
		<div class="form-box login">
			<h2>Welcome Back!</h2>
			<?php
            	if(isset($_SESSION['status'])&& $_SESSION['status']!='') { 
                    echo'<h4 class="bg-danger text-white">'.$_SESSION['status'].'</h4>';
                    unset($_SESSION['status']);
                }
            ?>
			<form class="user" action="logincode.php" method="POST">
				<div class="input-box">
					<span class="icon"><ion-icon name="mail"></ion-icon></span>
					<input type="email" name="u_email" required>
					<label >E-mail</label>
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input type="password" name="u_password" required>
					<label >Password</label>
				</div>
				<div class="remember-forgot">
					<label><input type="checkbox"> Remember me</label>
					<a href="#">Forgot Password</a>
				</div>
				<button type="submit" name="userloginbtn" class="btn btn-primary btn-user btn-block" class="btn">Sign In</button>
				<div class="signin-register">
					<p>New to MovieMagic?<a href="#" class="register-link"> Sign Up Now</a></p>
				</div>
			</form>
		</div>
		<div class="form-box register">
			<h2>Ready to Watch?</h2>
			<form action="code.php" method="POST">
				<div class="input-box">
					<span class="icon"><ion-icon name="person"></ion-icon></span>
					<input type="text" id="username" name="u_username" required>
					<label >Name</label>
					
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="mail"></ion-icon></span>
					<input type="email" id="email" name="u_email" required>
					<label >E-mail</label>
					
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input type="password" id="password" name="u_password" required>
					<label >Password</label>
					
				</div>
                <div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input style="border-bottom: 2px solid #d8d2d2;" type="password" id="cpassword" name="u_cpassword" required>
					<label >Confirm Password</label>
					
				</div>
				<div class="remember-forgot">
					<label><input type="checkbox" id="terms" required> I agree to the terms &conditions</label>
				</div>
				<button type="submit" value="submit" name="userregistration" class="btn btn-primary" class="btn">Sign Up</button>
				<div class="signin-register">
					<p>Already have account<a href="#" class="signin-link"> Sign In</a></p>
				</div>
                <div class="user-type" hidden>
					<input type="text" name="u_usertype" value="user">
				</div>
			</form>
		</div>
	</div>
	<script  type="text/javascript" src="js/Login.js"></script>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>