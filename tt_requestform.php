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
function getDropdownOptions($conn, $table, $valueField, $labelField, $additionalFields = array(), $condition = null) {
    $options = array();
    $fields = array_merge([$valueField, $labelField], $additionalFields);
    $sql = "SELECT " . implode(", ", $fields) . " FROM $table";

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Creation Request</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your theme's stylesheet -->
</head>
<style>
/* Reset some default styles */
body, h1, form, label, input, select {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    width: 80%;
    max-width: 460px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 24px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
    margin-top: 1rem;
}

form {
    display: grid;
    gap: 10px;
}

label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
    font-weight: bold;
}

input, select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #B22222;
    color: #fff;
    cursor: pointer;
}
</style>
<body>

    <div class="container">
        <h1><center>Term test Creation Request Form</center></h1>

        <form action="process_tt_re.php" method="post">
            <label for="department_id">Department:</label>
            <select name="department_id" id="department_id" required>
                <?php foreach ($departmentOptions as $department) : ?>
                    <option value="<?php echo $department['department_id']; ?>"><?php echo $department['department_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="acedamic_year">Academic year:</label>
            <select name="acedamic_year" id="acedamic_year" required>
                <!-- Script will populate academic years here -->
            </select>

            <label for="subject_id">Subject:</label>
            <select name="subject_id" id="subject_id" required>
                <!-- Subjects will be dynamically populated based on the selected department -->
            </select>

            <label for="test">Test:</label>
            <select name="test" required>
                <option value="I">I</option>
                <option value="II">II</option>
            </select>

            <label for="marks">Marks:</label>
            <select name="marks" required>
                <option value="30">30</option>
                <option value="25">25</option>
            </select>

            <label for="semester">Semester:</label>
            <select name="semester" required>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
                <option value="VI">VI</option>
                <option value="VII">VII</option>
                <option value="VIII">VIII</option>
            </select>

            <label for="exam_date">Exam Date:</label>
            <input type="date" name="exam_date" required>

            <label for="year">Year:</label>
            <select name="year" required>
                <option value="FY">FY</option>
                <option value="SY">SY</option>
                <option value="DSY">DSY</option>
                <option value="TY">TY</option>
                <option value="LY">LY</option>
            </select>

            <input type="hidden" name="status" value="Not Prepared">

            <input type="submit" value="Submit Request"><br>
        </form>

        <script>

            course_code=document.getElementById('Course_code');
            document.addEventListener('DOMContentLoaded', function () {
                var currentYear = new Date().getFullYear();
                var academicYearDropdown = document.getElementById('acedamic_year');

                for (var i = 2; i >= 0; i--) {
                    var startYear = currentYear - i;
                    var endYear = startYear + 1;
                    var academicYear = startYear.toString().substring(2) + '-' + endYear.toString().substring(2);

                    var option = document.createElement('option');
                    option.value = academicYear;
                    option.text = academicYear;
                    academicYearDropdown.add(option);
                }

                // Fetch subjects when the document is fully loaded
                fetchSubjects();
            });

            document.getElementById('department_id').addEventListener('change', function () {
                // Fetch subjects when the department is selected
                fetchSubjects();
            });

       

            function fetchSubjects() {
                var departmentId = document.getElementById('department_id').value;
                var subjectDropdown = document.getElementById('subject_id');
                var courseCodeInput = document.getElementById('Course_code');

                // Clear previous options
                subjectDropdown.innerHTML = '<option value="">Select Subject</option>';

                // Fetch subjects based on the selected department
                fetch('get_subjects_pr.php?department_id=' + departmentId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subject => {
                            var option = document.createElement('option');
                            option.value = subject.Course_code; //
                           
                            option.text = subject.subject_name;

                            subjectDropdown.add(option);
                        });

                        // Set the course code for the first subject in the list
                        courseCodeInput.value = data.length > 0 ? data[0].course_code : '';
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            }
        </script>

    </div>

</body>
</html>
