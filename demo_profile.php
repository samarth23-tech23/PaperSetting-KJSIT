<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2, p {
            color: #343a40;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #fff;
            background-color: #343a40;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #555555;
        }

        .profile-picture {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header class="bg-dark text-light">
        <h1>Teacher Profile</h1>
    </header>
    <section class="container bg-light text-dark mt-4 p-4">
        <div class="row">
            <div class="col-md-4 text-center">
                <!-- Profile Picture -->
                <img src="profile-picture.jpg" alt="Profile Picture" class="profile-picture">
            </div>
            <div class="col-md-8">
                <!-- Teacher Information -->
                <h2 class="mb-4">Teacher Name</h2>
                <p class="text-muted">Email: teacher@example.com</p>
                <p class="text-muted">Location: City, Country</p>
                <p class="text-muted">Member Since: January 1, 2020</p>
            </div>
        </div>

        <!-- Subjects Taught Section -->
        <div class="mt-4">
            <h2>Subjects Taught:</h2>
            <ul>
                <li>Subject 1</li>
                <div class="mb-3">
                    <a class="btn btn-dark" href="co_bt.php?subject_id=1">Set CO and BT Levels</a>
                    <a class="btn btn-dark" href="form2.php?subject_id=1&teacher_id=1">Set Question Bank</a>
                </div>
                <li>Subject 2</li>
                <div class="mb-3">
                    <a class="btn btn-dark" href="co_bt.php?subject_id=2">Set CO and BT Levels</a>
                    <a class="btn btn-dark" href="form2.php?subject_id=2&teacher_id=1">Set Question Bank</a>
                </div>
                <!-- Add more subjects as needed -->
            </ul>
        </div>
    </section>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>