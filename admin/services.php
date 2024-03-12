<!DOCTYPE html>
<html>
    <head>
        <title>MovieMagic | Where Every Frame tells a Story</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wgt@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
            *{
                margin:0;
                padding:0;
                box-sizing: border-box;
                font-family: 'Josefin Sans', sans-serif;
            }
            body{
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background: #191919;
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
            .navigation .btnLogin-popup{
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
            .title h1{
                text-align: center;
                padding-top: 50px;
                color: #01939c;
                font-size: 42px;
                text-transform: capitalize;
                margin-bottom: 20px;
            }
            .title h1::after{
                content:"";
                height:4px;
                width:230px;
                background-color: #01939c;
                display: block;
                margin: auto;
            }
            .services{
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items:ceter;
                margin: 75px auto;
                text-align: center;
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
            .card h2{
                font-size: 28px;
                color: #01939c;
                margin-bottom: 20px;
            }
            .card p{
                text-align:left;
                font-size: 17px;
                margin-bottom: 30px;
                line-height:1.5;
                color: #fff;           
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
			<a href="services.php">Services</a>
			<a href="contact.php">Contact</a>
			<button onclick="redirectToUserLogin()" style="height: 50px;background: transparent;border: .5px solid #3cb2e9;outline: none;border-radius: 40px;cursor: pointer;font-size: 1.1em;color: #3cb2e9;font-weight: 500;margin-left: 40px;transition: .5s;">Sign In</button>
		</nav>
	</header>
        <div class="section">
            <div class="title">
                <h1>Our Services</h1>
            </div>
            <div class="services">
            <?php
                include( "database/dbconfig.php" ); // Connect

                $query = "SELECT * FROM service";
                $query_run = mysqli_query($connection,$query);
                        
                if(mysqli_num_rows($query_run)>0)
                {
                    foreach($query_run as $row)
                    {
            ?>
                <div class="card">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <button class="btn btn-primary">Read More</button>
                    <h2>
                </div>
                <?php
                        }
                    }
                    else{
                        echo "No Record Found";
                    }
                ?>
            </div>
        </div>
        <script>
        function redirectToUserLogin() {
            // Redirect to the userlogin page
            window.location.href = 'userlogin.php';
        }
    </script>
    </body>
</html>