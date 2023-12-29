<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Papers</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card {
            width: 80rem;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 24px;
            background: #FFFFFF;
    }

        h2 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #B22222;
            color: white;
        }

        .filter-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
        }

        .filter-form label {
            margin-right: 10px;
        }

        select, input[type="submit"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #B22222;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #B22222;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        td button {
            padding: 8px;
            background-color: #B22222;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        td button:hover {
            background-color: #800000;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInTable {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Apply animations */
        h2, .filter-form, table {
            animation: fadeIn 1s ease-in-out;
        }

        table {
            animation: fadeInTable 1s ease-in-out;
        }
    </style>
</head>
<body>
    <?php
        // Assuming you have a database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kjsit";
        $port = 3307;

        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Function to fetch options for dropdowns
        function getDropdownOptions($conn, $table, $valueField, $labelField, $condition = null) {
            $options = array();
            $sql = "SELECT $valueField, $labelField FROM $table";

            if ($condition !== null) {
                $sql .= " WHERE $condition";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $options[] = $row;
                }
            }

            return $options;
        }

        // Fetch department options
        $departmentOptions = getDropdownOptions($conn, 'departments', 'department_id', 'department_name');

        // Fetch distinct years from the table
        $yearOptions = getDropdownOptions($conn, 'exam_schedule', 'year', 'year', '1 GROUP BY year');
    ?>

   

    <!-- Filter Form -->
    <form class="filter-form" method="get" action="">
        <label for="department_filter">Filter by Department:</label>
        <select name="department_filter" id="department_filter">
            <option value="">All</option>
            <?php foreach ($departmentOptions as $department) : ?>
                <option value="<?php echo $department['department_id']; ?>"><?php echo $department['department_name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="year_filter">Filter by Year:</label>
        <select name="year_filter" id="year_filter">
            <option value="">All</option>
            <?php foreach ($yearOptions as $year) : ?>
                <option value="<?php echo $year['year']; ?>"><?php echo $year['year']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="status_filter">Filter by Status:</label>
        <select name="status_filter" id="status_filter">
            <option value="">All</option>
            <option value="Not Prepared">Not Prepared</option>
            <option value="Prepared">Prepared</option>
            <!-- Add other status options as needed -->
        </select>

        <input type="submit" value="Apply Filters">
    </form>

    <div class="card">
    <h2><center>Scheduled Papers</center></h2>
    <?php
        // PHP code to fetch and display filtered data
        $sql = "SELECT es.schedule_id, es.exam_time, d.department_name, es.exam_type, es.semester, es.exam_date, es.max_marks, es.status, s.subject_name, es.year
                FROM exam_schedule es
                INNER JOIN departments d ON es.department_id = d.department_id
                INNER JOIN subjects s ON es.subject_id = s.subject_id";

        $whereConditions = array();

        if (isset($_GET['department_filter']) && !empty($_GET['department_filter'])) {
            $whereConditions[] = "d.department_id = " . $_GET['department_filter'];
        }

        if (isset($_GET['year_filter']) && !empty($_GET['year_filter'])) {
            $whereConditions[] = "es.year = '" . $_GET['year_filter'] . "'";
        }

        if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
            $whereConditions[] = "es.status = '" . $_GET['status_filter'] . "'";
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }

        $sql .= " ORDER BY es.exam_date ASC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Exam Time</th>';
            echo '<th>Department</th>';
            echo '<th>Exam Type</th>';
            echo '<th>Semester</th>';
            echo '<th>Exam Date</th>';
            echo '<th>Subject</th>';
            echo '<th>Year</th>';
            echo '<th>Marks</th>';
            echo '<th>Status</th>';
            echo '<th>Actions</th>';
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['exam_time'] . "</td>";
                echo "<td>" . $row['department_name'] . "</td>";
                echo "<td>" . $row['exam_type'] . "</td>";
                echo "<td>" . $row['semester'] . "</td>";
                echo "<td>" . $row['exam_date'] . "</td>";
                echo "<td>" . $row['subject_name'] . "</td>"; // Display subject name
                echo "<td>" . $row['year'] . "</td>";
                echo "<td>" . $row['max_marks'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>";
                if ($row['status'] === 'Not Prepared') {
                    echo "<button onclick=\"window.location.href='structure.php";
                    echo "?schedule_id=" . $row['schedule_id'];
                    echo "&subject_name=" . $row['subject_name'];
                    echo "'\">Set Paper</button>";
                } elseif ($row['status'] === 'Prepared') {
                    echo "<button>Print</button>";
                } else {
                    echo "No Action";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo '</table>';
        } else {
            echo "<p>No scheduled papers</p>";
        }

        // Close the connection
        $conn->close();
    ?>
    </div>

    <!-- Other forms for paper creation, edit, and deletion -->
</body>
</html> 