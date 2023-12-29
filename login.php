<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white; 
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 300px;
            opacity: 0; 
            animation: fadeIn 1s ease-in-out forwards;
            width: 23rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card h1 {
            color: #B22222; /* Set text color to red */
        }

        .label-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px; /* Increased margin for better spacing */
        }

        .label-icon i {
            color: #B22222; /* Set icon color to red */
            margin-right: 10px; /* Added margin for spacing between icon and input */
        }

        input {
            width: 100%;
            padding: 10px; /* Increased padding for better appearance */
            box-sizing: border-box;
            background: transparent; /* Set transparent background */
            border: none;
            border-bottom: 2px solid #B22222; /* Set line-style border to red */
            color: #B22222; /* Set input text color to red */
            border-radius: 0; /* Remove border-radius to make it look like a line */
            outline: none; /* Remove default input focus outline */
        }

        input[type="submit"] {
            background: #B22222; /* Set submit button color to red */
            color: #FFFFFF; /* Set submit button text color to white */
            cursor: pointer;
            border: none;
            padding: 10px 20px; /* Adjusted padding for better button appearance */
            border-radius: 5px; /* Added border-radius to the button */
        }

        input[type="submit"]:hover {
            background: #800000; /* Set submit button hover color (darker shade of red) */
        }

        .forget-password, .register-link {
            color: #B22222; /* Set link color to red */
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        .register-link:hover {
            color: #800000; /* Set register link hover color (darker shade of red) */
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-body">
            <h1>Login</h1><br>
            <form action="process_login.php" method="post">
                <div class="label-icon">
                    <i class="fas fa-envelope" style="font-size: 1.5em;"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div><br>
                <div class="label-icon">
                    <i class="fas fa-lock" style="font-size: 1.5em;"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div><br>
                <input type="submit" value="Login">
                <br><br>
                <a href="#" class="forget-password">Forget Password?</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js scripts (required for Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>