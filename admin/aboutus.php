<!DOCTYPE html>
<html>
    <head>
        <title>MovieMagic | Where Every Story tells a Story</title>
        <link rel="preconnect" href="http://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wgt@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
        <style>
            *{
                padding: 0;
                margin: 0;
                font-family: 'Josefin Sans', sans-serif;
                box-sizing: border-box;
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
            .about{
                width: 100%;
                background-size: cover;
                padding: 78px 0px;
                background-color: #191919;
            }
            .about img{
                height: auto;
                width: 420px;
            }
            .about-text{
                width: 550px;
            }
            .main{
                width: 1130px;
                max-width: 95%;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-around;
            }
            .about-text h1{
                color: #01939c;
                font-size: 80px;
                text-transform: capitalize;
                margin-bottom: 20px;
            }
            .about-text h5{
                color: #01939c;
                font-size: 20px;
                text-transform: capitalize;
                margin-bottom: 25px;
                letter-spacing: 2px;
            }
            .about-text p{
                color: white;
                letter-spacing: 1px;
                line-height: 28px;
                font-size: 18px;
                margin-bottom: 45px;
            }
            button{
                background: transparent;
                color: #01939c;
                text-decoration: none;
                border: 2px solid #01939c;
                font-weight: bold;
                padding: 13px 30px;
                border-radius: 30px;
                transition: .4s;
            }
            button:hover{
                background: #01939c;
                color:#162938;
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
			<a href="#">Services</a>
			<a href="#">Contact</a>
			<button onclick="redirectToUserLogin()" style="height: 50px;background: transparent;border: .5px solid #3cb2e9;outline: none;border-radius: 40px;cursor: pointer;font-size: 1.1em;color: #3cb2e9;font-weight: 500;margin-left: 40px;transition: .5s;">Sign In</button>
		</nav>
	</header>
        <section class="about">
            <div class="main">
                <img src="Images%20and%20Videos/mainlogo.jpeg">
                <div class="about-text">
                    <?php
                        include( "database/dbconfig.php" ); // Connect

                        $query = "SELECT * FROM about";
                        $query_run = mysqli_query($connection,$query);
                        
                        if(mysqli_num_rows($query_run)>0)
                        {
                            foreach($query_run as $row)
                            {
                    ?>
 
                    <h1><?php echo $row['title'];?></h1>
                    <h5><?php echo $row['subtitle'];?></h5>
                    <p><?php echo $row['description'];?></p>
                    <button onclick="redirectToHomePage()" class="btn btn-primary">Shall we explore MovieMagic for our movie night?</button>
                    <?php
                            }
                        }
                        else{
                            echo "No Record Found";
                        }
                    ?>
                </div>
            </div>
        </section>
        <script>
        function redirectToUserLogin() {
            // Redirect to the userlogin page
            window.location.href = 'userlogin.php';
        }
        function redirectToHomePage() {
            // Replace 'home.html' with the actual file name or path of your home page
            window.location.href = '<?php echo $row['links'];?>';
        }
    </script>
    </body>
</html>