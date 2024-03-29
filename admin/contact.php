<?php
    include("database/dbconfig.php")
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MovieMagic | Where Every Frame tells  a Story</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            *{
                margin: 0px;
                padding: 0px;
                box-sizing: border-box;
                font-family: sans-serif;
            }
            body{
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #191919;
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
                background: #191919;
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
            .contact{
                min-height:100vh;
                background-color: #191919;
                padding: 50px;
                text-align: center;
            }
            .container{
                max-width: 800px;
                margin:0 auto;
            }
            .container h2{
                margin-top: 24px;
                font-size: 36px;
                margin-bottom: 40px;
                color: #01939c;
            }
            /*.contact-wrapper{
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-gap:  30px;
            }*/
            .contact-form{
                text-align: left;
                
            }
            .contact-form h3{
                font-size: 24px;
                margin-bottom: 20px;
                color: #01939c;
            }
            .form-group{
                margin-bottom: 20px;
            }
            input, textarea{
                width: 100%;
                padding: 12px;
                border-radius: 8px;
                border: none;
                background-color: #191919;
                color: #d8d2d2;
            }
            input:focus, textarea:focus{
                outline: none;
                box-shadow: 0 0 8px #bbb;
            }
            button{
                background: transparent;
                color: #01939c;
                text-decoration: none;
                border: 2px solid #01939c;
                font-weight: bold;
                padding: 13px 30px;
                border-radius: 30px;
                cursor: pointer;
            }
            button:hover{
                background: #01939c;
                color:#162938;
                cursor: pointer;
            }
            .contact-info h3{
                font-size:24px;
                margin-bottom: 20px;
                color: #01939c;
            }
            .contact-info p{
                margin-bottom: 10px;
                color: #01939c;
            }
            .contact-info i{
                color: #01939c;
                margin-right: 10px;
            }
            .card{
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-direction: column;
                margin: 0px 10px;
                padding: 20px 20px;
                background: transparent;
			    background-color: rgba(59, 57, 57, 0.5);
                border-radius: 10px;
                cursor: pointer;
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
                <button onclick="redirectToUserLogin()" style="height: 50px;background: transparent;border: .5px solid #3cb2e9;outline: none;border-radius: 40px;cursor: pointer;font-size: 1.1em;color: #3cb2e9;font-weight: 500;margin-left: 40px;transition: .5s;">Sign In</button>
            </nav>
        </header>
        <section class="contact">
            <div class="container">
                <div class="card">
                    <h2>Contact US</h2>
                    <div class="contact-wrapper">
                        <div class="contact-form">
                            <h3>Send US a Message</h3>
                            <form action="code.php" method="POST">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Your Email Address">
                                </div>
                                <div class="form-group">
                                    <textarea name="message" placeholder="Your Message"></textarea>
                                </div>
                                <button type="submit" name="messagebtn">Send Message</button><br>
                            </form>
                        </div><br>
                        <div class="contact-info">
                            <h3>Our Contact Information</h3>
                            <p><i class="fas fa-phone"></i>+977-9810031424<br>+977-9847398065</p>
                            <p><i class="fas fa-envelope"></i>moviemagic02@gmail.com</p>
                            <p><i class="fas fa-map-marker-alt"></i>Paknajol Marg, Kathmandu 21010</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
        function redirectToUserLogin() {
            // Redirect to the userlogin page
            window.location.href = 'userlogin.php';
        }
    </script>
    </body>
</html>