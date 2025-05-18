<?php 
session_start();
// Remove any existing output buffering
if (ob_get_length()) ob_end_clean();
?>
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
		
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}
		
		body {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
						url("ImagesandVideos/Backgroud_Images/BG7.jpg") no-repeat center/cover fixed;
		}

		/* Navigation Bar - Floating Style */
		header {
			position: fixed;
			top: 12px;
			left: 50%;
			transform: translateX(-50%);
			width: 100%;
			padding: 15px 40px;
			height: 70px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			z-index: 99;
			background: rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5);
			transition: all 0.3s ease;
			margin: 0 auto;
		}

		.logo {
			font-size: 1.8em;
			color: #3cb2e9;
			font-weight: 700;
			letter-spacing: 1px;
			text-decoration: none;
			transition: all 0.3s ease;
		}

		.logo:hover {
			color: #fff;
			text-shadow: 0 0 8px rgba(60, 178, 233, 0.6);
		}

		.navigation {
			display: flex;
			align-items: center;
			gap: 30px;
		}

		.navigation a {
			color: rgba(255, 255, 255, 0.8);
			font-size: 1em;
			font-weight: 500;
			text-decoration: none;
			position: relative;
			padding: 5px 0;
			transition: all 0.3s ease;
		}

		.navigation a:hover {
			color: #3cb2e9;
		}

		.navigation a::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 0;
			width: 0;
			height: 2px;
			background: #3cb2e9;
			transition: width 0.3s ease;
		}

		.navigation a:hover::after {
			width: 100%;
		}

		.btnLogin-popup {
			padding: 8px 20px;
			background: transparent;
			border: 2px solid #3cb2e9;
			border-radius: 30px;
			color: #3cb2e9;
			font-size: 1em;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.btnLogin-popup:hover {
			background: #3cb2e9;
			color: #111;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(60, 178, 233, 0.3);
		}

		/* Keep existing wrapper and form styles */
		.wrapper {
			position: relative;
			margin-top: 60px;
			width: 450px;
			height: 440px;
			background: transparent;
			background-color: rgba(0, 0, 0, .5);
			backdrop-filter: blur(20px);
			border: none;
			border-radius: 20px;
			box-shadow: 0 0 30px rgba(0, 0, 0, .5);
			display: flex;
			justify-content: center;
			align-items: flex-start;
			padding-top: 30px;
			overflow: hidden;
			transform: scale(0);
			transition: transform .5s ease, height .2s ease;
		}

		.wrapper.active {
			height: 650px;
			padding-top: 20px;
		}	
		.wrapper.active-popup{
			transform: scale(1);
		}
		.wrapper .form-box {
			width: 100%;
			padding: 0 40px; /* Reduced vertical padding */
			margin-top: -10px; /* Adjust this value to fine-tune position */
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
			margin-top: 10px; 
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
			width: 1rem;
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
            margin-bottom: 15px;
		}
		.input-box{
			position: relative;
			width: 100%;
			height: 65px;
			border-bottom: 2px solid #d8d2d2;
			margin-bottom: 1rem;
			margin-top: 0;
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
			width: 20rem;
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
			margin: 5px 0 15px;
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
			margin-left: 4rem;
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
			margin-bottom: 0.5rem;
		}
		.signin-register{
			font-size: .9em;
			color: #d8d2d2;
			text-align: center;
			font-weight: 500;
			
            margin-bottom: 0.5rem;
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
		.error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
            display: none;
        }
		/* Location input specific styles */
		.location-container {
			position: relative;
			width: 100%;
			display: flex;
			align-items: center;
			margin-bottom: 1rem;
		}

		.location-input {
			flex-grow: 1;
			height: 65px;
			background: transparent;
			border: none;
			border-bottom: 2px solid #d8d2d2;
			outline: none;
			font-size: 1.1em;
			color: #3cb2e9;
			font-weight: 500;
			padding: 10px 15px 0;
			width: calc(100% - 120px); /* Adjust based on button width */
		}

		.location-label {
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

		.location-input:focus ~ .location-label,
		.location-input:valid ~ .location-label {
			top: -5px;
			height: 0;
			overflow: visible;
		}

		.location-btn {
			position: absolute;
			right: 0;
			width: 110px;
			height: 35px;
			background: #3cb2e9;
			border: none;
			outline: none;
			border-radius: 6px;
			cursor: pointer;
			font-size: 0.8em;
			color: #0a0a0a;
			font-weight: 500;
			transition: background 0.3s;
		}

		.location-btn:hover {
			background: #2a9fd8;
		}

		/* Add these new styles for the loading spinner */
		.btn {
			position: relative;
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 8px;
		}

		.btn .spinner {
			display: none;
			width: 20px;
			height: 20px;
			border: 3px solid rgba(255, 255, 255, 0.3);
			border-radius: 50%;
			border-top-color: #fff;
			animation: spin 1s ease-in-out infinite;
		}

		.btn.loading {
			pointer-events: none;
			opacity: 0.8;
		}

		.btn.loading .spinner {
			display: block;
		}

		.btn.loading .btn-text {
			display: none;
		}

		@keyframes spin {
			to {
				transform: rotate(360deg);
			}
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
            	if(isset($_SESSION['status']) && $_SESSION['status'] != '') { 
                    echo '<h4 class="bg-danger text-white" style="padding: 10px;">'.$_SESSION['status'].'</h4>';
                    unset($_SESSION['status']);
                }
            ?>
			<form action="logincode.php" method="POST" id="loginForm">
				<div class="input-box">
					<span class="icon"><ion-icon name="mail"></ion-icon></span>
					<input type="email" name="u_email" required>
					<label>E-mail</label>
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input type="password" name="u_password" required>
					<label>Password</label>
				</div>
				<input type="hidden" id="login_location" name="location">
				<input type="hidden" id="login_latitude" name="latitude">
				<input type="hidden" id="login_longitude" name="longitude">
				<div class="remember-forgot">
					<label><input type="checkbox"> Remember me</label>
					<a href="#">Forgot Password</a>
				</div>
				<button type="submit" name="userloginbtn" value="1" class="btn" id="loginBtn">
					<span class="spinner"></span>
					<span class="btn-text">Sign In</span>
				</button>
				<div class="signin-register">
					<p>New to MovieMagic?<a href="#" class="register-link"> Sign Up Now</a></p>
				</div>
			</form>
		</div>
		<div class="form-box register">
			<h2>Ready to Watch?</h2>
			<form id="registerForm" action="code.php" method="POST" onsubmit="return validateForm()">
				<div class="input-box">
					<span class="icon"><ion-icon name="person"></ion-icon></span>
					<input type="text" id="username" name="u_username" required>
					<label>Username</label>
					<span class="error-message" id="username-error"></span>
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="mail"></ion-icon></span>
					<input type="email" id="email" name="u_email" required>
					<label>E-mail</label>
					<span class="error-message" id="email-error"></span>
				</div>
				<div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input type="password" id="password" name="u_password" required>
					<label>Password</label>
					<span class="error-message" id="password-error"></span>
				</div>
                <div class="input-box">
					<span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
					<input style="border-bottom: 2px solid #d8d2d2;" type="password" id="cpassword" name="u_cpassword" required>
					<label>Confirm Password</label>
					<span class="error-message" id="cpassword-error"></span>
				</div>
				<div class="location-container">
					<input type="text" class="location-input" id="register_location" name="location" required>
					<label class="location-label">Location</label>
					<button type="button" class="location-btn" onclick="getRegisterLocation()">Get Location</button>
				</div>
				<input type="hidden" id="register_latitude" name="latitude">
				<input type="hidden" id="register_longitude" name="longitude">
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
	<script>
        document.getElementById('username').addEventListener('input', function () {
            const username = this.value.trim();
            const usernameError = document.getElementById('username-error');

            if (username.length < 3 || username.length > 20) {
                usernameError.innerText = 'Username must be 3-20 characters long.';
                usernameError.style.display = 'block';
            } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                usernameError.innerText = 'Username can only contain letters, numbers, and underscores.';
                usernameError.style.display = 'block';
            } else {
                usernameError.style.display = 'none';
            }
        });

        document.getElementById('email').addEventListener('input', function () {
            const email = this.value.trim();
            const emailError = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                emailError.innerText = 'Please enter a valid email address.';
                emailError.style.display = 'block';
            } else {
                emailError.style.display = 'none';
            }
        });

        document.getElementById('password').addEventListener('input', function () {
            const password = this.value.trim();
            const passwordError = document.getElementById('password-error');

            if (password.length < 8) {
                passwordError.innerText = 'Password must be at least 8 characters long.';
                passwordError.style.display = 'block';
            } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(password)) {
                passwordError.innerText = 'Password must include at least one uppercase letter, one lowercase letter, and one number.';
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        });

        document.getElementById('cpassword').addEventListener('input', function () {
            const cpassword = this.value.trim();
            const password = document.getElementById('password').value.trim();
            const cpasswordError = document.getElementById('cpassword-error');

            if (password !== cpassword) {
                cpasswordError.innerText = 'Passwords do not match.';
                cpasswordError.style.display = 'block';
            } else {
                cpasswordError.style.display = 'none';
            }
        });

        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const cpassword = document.getElementById('cpassword').value.trim();
            const terms = document.getElementById('terms').checked;

            const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            let isValid = true;

            if (!usernameRegex.test(username)) {
                document.getElementById('username-error').innerText = 'Username must be 3-20 characters long and can only contain letters, numbers, and underscores.';
                document.getElementById('username-error').style.display = 'block';
                isValid = false;
            }

            if (!emailRegex.test(email)) {
                document.getElementById('email-error').innerText = 'Please enter a valid email address.';
                document.getElementById('email-error').style.display = 'block';
                isValid = false;
            }

            if (!passwordRegex.test(password)) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one number.';
                document.getElementById('password-error').style.display = 'block';
                isValid = false;
            }

            if (password !== cpassword) {
                document.getElementById('cpassword-error').innerText = 'Passwords do not match.';
                document.getElementById('cpassword-error').style.display = 'block';
                isValid = false;
            }

            if (!terms) {
                alert('You must agree to the terms and conditions.');
                isValid = false;
            }

            return isValid;
        }

        async function handleLogin(event) {
            event.preventDefault();
            
            try {
                const locationData = await ipLocationTracker.getLocationFromIP();
                if (locationData) {
                    document.getElementById('location_data').value = JSON.stringify(locationData);
                }
            } catch (error) {
                console.error('Error getting location:', error);
            }

            event.target.submit();
            return false;
        }
	</script>
	<script>
    function getLocationForLogin() {
        return new Promise((resolve, reject) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                            const data = await response.json();
                            
                            document.getElementById('login_latitude').value = lat;
                            document.getElementById('login_longitude').value = lng;
                            document.getElementById('login_location').value = data.display_name || `Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                            
                            console.log('Location data set:', {
                                location: document.getElementById('login_location').value,
                                latitude: lat,
                                longitude: lng
                            });
                            
                            resolve();
                        } catch (error) {
                            console.error('Error getting location name:', error);
                            document.getElementById('login_location').value = `Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                            resolve();
                        }
                    },
                    function(error) {
                        console.error('Error getting location:', error);
                        reject(error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                reject(new Error('Geolocation is not supported by this browser.'));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        console.log('Login form found:', loginForm);
        
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                console.log('Form submit event triggered');
                e.preventDefault();
                
                // Add loading state to button
                loginBtn.classList.add('loading');
                
                try {
                    console.log('Getting location...');
                    await getLocationForLogin();
                    console.log('Location obtained, form data:', {
                        location: document.getElementById('login_location').value,
                        latitude: document.getElementById('login_latitude').value,
                        longitude: document.getElementById('login_longitude').value,
                        email: document.querySelector('input[name="u_email"]').value,
                        password: document.querySelector('input[name="u_password"]').value,
                        userloginbtn: '1'
                    });
                    
                    // Submit the form after a short delay
                    setTimeout(() => {
                        console.log('Submitting form...');
                        // Create a new FormData object
                        const formData = new FormData(this);
                        formData.append('userloginbtn', '1');
                        
                        // Submit using fetch
                        fetch('logincode.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            console.log('Response received:', response);
                            if (response.redirected) {
                                window.location.href = response.url;
                            } else {
                                window.location.href = 'HomePage.php';
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting form:', error);
                            loginBtn.classList.remove('loading'); // Remove loading state on error
                            this.submit(); // Fallback to regular form submission
                        });
                    }, 1000);
                } catch (error) {
                    console.error('Error during form submission:', error);
                    loginBtn.classList.remove('loading'); // Remove loading state on error
                    this.submit(); // Fallback to regular form submission
                }
            });
        } else {
            console.error('Login form not found!');
        }
    });

    function getRegisterLocation() {
        if (navigator.geolocation) {
            const locationInput = document.getElementById('register_location');
            const locationBtn = locationInput.nextElementSibling.nextElementSibling;
            
            locationBtn.textContent = 'Getting location...';
            locationBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('register_latitude').value = lat;
                    document.getElementById('register_longitude').value = lng;
                    
                    locationInput.value = `Fetching address... (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                    
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                locationInput.value = data.display_name;
                            } else {
                                locationInput.value = `Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                            }
                            locationInput.focus();
                        })
                        .catch(error => {
                            console.error('Error getting location name:', error);
                            locationInput.value = `Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                        })
                        .finally(() => {
                            locationBtn.textContent = 'Get Location';
                            locationBtn.disabled = false;
                        });
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                    locationBtn.textContent = 'Get Location';
                    locationBtn.disabled = false;
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }
</script>
<script>
		document.addEventListener('DOMContentLoaded', function() {
			const particlesContainer = document.createElement('div');
			particlesContainer.className = 'particles';
			document.body.appendChild(particlesContainer);
			
			const particleCount = 30;
			
			for (let i = 0; i < particleCount; i++) {
				const particle = document.createElement('div');
				particle.className = 'particle';
				
				// Random properties
				const size = Math.random() * 10 + 5;
				const posX = Math.random() * window.innerWidth;
				const posY = Math.random() * window.innerHeight;
				const delay = Math.random() * 5;
				const duration = Math.random() * 20 + 10;
				const opacity = Math.random() * 0.5 + 0.1;
				
				particle.style.width = `${size}px`;
				particle.style.height = `${size}px`;
				particle.style.left = `${posX}px`;
				particle.style.top = `${posY}px`;
				particle.style.animationDelay = `${delay}s`;
				particle.style.animationDuration = `${duration}s`;
				particle.style.opacity = opacity;
				
				particlesContainer.appendChild(particle);
			}
		});
	</script>
</body>
</html>