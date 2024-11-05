<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;

        }

        body {
            color: rgb(243, 241, 241);
            background-color: #ed8505;
            width: inherit;
        }

        section {
            display: flex;
            background-color: white;
            height: 15vh;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);


        }
        .logo {
    position: absolute; 
    top: -13px; 
    left: 20px; 
}

.logo img {
    width: 180px; 
    height: auto; 
}


        .register_button,
        .login_button {
            position: relative;
            width: 105px;
            height: 5vh;
            margin: 27px;
            left: 70%;
            text-align: center;
            padding: 2px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bold;
            color: black;
            border: 1px solid #f28a0a;

        }

        .register_button:hover {
            color: white;
            background-color: #f28a0a;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }

        .login_button:hover {
            color: white;
            background-color: #f28a0a;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }

        a {
            text-decoration: none;
        }

        /* WELCOME  COLOR SEPARATION AND COLORING */
        /* WINES PICTURES */
        .container {
            height: 85vh;
            width: 100vw;
            background: linear-gradient(to top left, #050505 50%, transparent 30%), url(./images/welcom.png);

            background-repeat: no-repeat;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);

        }

        h2 {
            color: white;
            text-align: center;
            position: absolute;
            top: 50%;
            bottom: 50%;
            left: 80%;
            
            transform: translate(-50%, -50%);
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 57px;
        }
    </style>
</head>

<body>
    <section>
        <div class="logo">
            <img src="./images/logo.png" alt="logo">
        </div>
        <a class="register_button" href="./index.php">Sign Up</a>
        <a class="login_button" href="./login.php">Log In</a>
    </section>

    <div class="container">

        <div class="textsection">
            <h2>WELCOME TO WINSPI </h2>
        </div>
    </div>
</body>

</html>