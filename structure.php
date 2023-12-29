<!DOCTYPE html>
<html>

<head>
    <style>
        .header-box {
            background-color: #f8f8f8;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .header-text {
            font-size: 18px;
            font-weight: bold;
        }

        .header-details {
            font-size: 14px;
        }

        .exam-info {
            margin-top: 20px;
            background-color: #e2e2e2;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .Instructions {
            margin-top: 20px;
            background-color: #e2e2e2;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #f8f8f8;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #e2e2e2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        select {
            width: 100%;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

          
    </style>
</head>

<body>

    <?php
    // Function to convert numbers into text
    function numberToText($number)
    {
        $numberMap = array(
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5=> 'five',
            6 => 'six',
            8 => 'eight'
          
        );

        return isset($numberMap[$number]) ? $numberMap[$number] : $number;
    }

    // Function to get subject ID from the database based on subject name
    function getSubjectIdFromDB($conn, $subjectName)
    {
        $sql = "SELECT subject_id FROM subjects WHERE subject_name = '$subjectName'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['subject_id'];
        }

        return null;
    }

    // Function to get subject details from the database
    function getSubjectDetailsFromDB($conn, $scheduleId)
    {
        $sql = "SELECT es.schedule_id, es.exam_time, d.department_name, es.exam_type, es.semester, es.exam_date, es.max_marks, es.status, es.year, s.course_code, s.subject_name
        FROM exam_schedule es
        INNER JOIN departments d ON es.department_id = d.department_id
        INNER JOIN subjects s ON es.subject_id = s.subject_id
        WHERE es.schedule_id = $scheduleId";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }

        return null;
    }

    // Function to get COs for a subject from the database
    function getCOsFromDB($conn, $subjectId)
    {
        $sql = "SELECT co_id FROM subject_co_mapping WHERE subject_id = $subjectId";
        $result = $conn->query($sql);

        $cos = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $coId = $row['co_id'];
                // Now, fetch CO details using $coId
                $coDetails = getCODetailsFromDB($conn, $coId);
                if ($coDetails !== null) {
                    $cos[] = $coDetails;
                }
            }
        }

        return $cos;
    }

    // Function to get BT Levels for a subject from the database
    function getBTLevelsFromDB($conn, $subjectId)
    {
        $sql = "SELECT bt_id FROM subject_bt_mapping WHERE subject_id = $subjectId";
        $result = $conn->query($sql);

        $btLevels = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $btId = $row['bt_id'];
                // Now, fetch BT Level details using $btId
                $btLevelDetails = getBTLevelDetailsFromDB($conn, $btId);
                if ($btLevelDetails !== null) {
                    $btLevels[] = $btLevelDetails;
                }
            }
        }

        return $btLevels;
    }

    // Function to get CO details from the database
    function getCODetailsFromDB($conn, $coId)
    {
        $sql = "SELECT co_id,co_number, co_description FROM cos WHERE co_id = $coId";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }

        return null;
    }

    // Function to get BT Level details from the database
    function getBTLevelDetailsFromDB($conn, $btId)
    {
        $sql = "SELECT bt_id, bt_description FROM bts WHERE bt_id = $btId";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }

        return null;
    }

    // Function to echo a question row
function echoQuestionRow($questionNumber, $dataRowNumber, $subjectId, $section)
{
    echo "<tr>";
    echo "<td>$questionNumber)</td>";
    echo "<td id='generatedQuestions_$dataRowNumber'></td>";
    echo "<td id='maxMarks_$dataRowNumber'></td>";
    echo "<td>";
    echo "<select name='co[]' id='coSelect_$dataRowNumber'>";
    echo "<option value='' disabled>Select CO</option>";

    $cos = getCOsFromDB($GLOBALS['conn'], $subjectId);

    if ($cos === null || empty($cos)) {
        echo "No COs found for subject ID: " . $subjectId;
    } else {
        foreach ($cos as $co) {
            echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] .   "</option>";
        }
    }

    echo "</select>";
    echo "</td>";
    echo "<td>";
    echo "<select name='bt_level[]' id='btSelect_$dataRowNumber'>";
    echo "<option value='' disabled>Select BT Level</option>";

    $btLevels = getBTLevelsFromDB($GLOBALS['conn'], $subjectId);

    foreach ($btLevels as $btLevel) {
        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
    }

    echo "</select>";
    echo "</td>";
    echo "<td>";
    echo "<button data-row='$dataRowNumber' onclick='generateQuestions(this)'>Generate</button>";
    echo "</td>";
    echo "</tr>";
}


    ?>
<div class="top">
    <div class="header-box">
        <div class="header-text">K. J. Somaiya Institute of Technology, Sion, Mumbai-22</div>
        <div class="header-details">(Autonomous College Affiliated to University of Mumbai)</div>
    </div>

    <?php
    // Establish a database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kjsit";
    $port = 3307;

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve schedule_id and subject_name from URL parameters
    $scheduleId = $_GET['schedule_id'];
    $subjectName = $_GET['subject_name'];

    // Use subject_name to get subject_id
    $subjectId = getSubjectIdFromDB($conn, $subjectName);

    // Use schedule_id to fetch details from the database
    $subjectDetails = getSubjectDetailsFromDB($conn, $scheduleId);

    if ($subjectDetails !== null) {
        ?>

        <div class="exam-info">
            <div class="header-details"><?php echo $subjectDetails['exam_time']; ?></div>
            <div class="header-details">(B.Tech) Program: <?php echo $subjectDetails['department_name']; ?> Scheme:II</div>
            <div class="header-text">Examination: <?php echo $subjectDetails['year']; ?> Semester: <?php echo $subjectDetails['semester']; ?></div>
            <div class="header-details">Course Code: <?php echo $subjectDetails['course_code']; ?> and Course Name: <?php echo $subjectDetails['subject_name']; ?></div>
            <div class="header-details" style="text-align: right;">Max. Marks: <?php echo $subjectDetails['max_marks']; ?></div>
            <div class="header-details">Duration: 2.5 Hours</div>
            <div class="header-details" style="text-align: left;">Date of Exam: <?php echo $subjectDetails['exam_date']; ?></div>
        </div>

    </div>
        <div class="page-break"></div>

        <div class="Instructions">
            <div class="header-details">Instructions:</div>
            <div class="header-details">(1) All questions are compulsory.</div>
            <div class="header-details">(2) Draw neat diagrams wherever applicable.</div>
            <div class="header-details">(3) Assume suitable data, if necessary.</div>
        </div>
        

        <table>
            <thead>
                <tr>
                    <th> </th>
                    <th> </th>
                    <th>Max Marks</th>
                    <th>CO </th>
                    <th>BT Level</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
// Loop through each section (Q1, Q2, Q3, Q4)
$dataRowCounter = 1; // Counter for the data-row attribute
for ($section = 1; $section <= 4; $section++) {
    // Display the section header row
    echo "<tr>";
    echo "<td>Q.$section</td>";
    echo "<td>Solve any " . numberToText(($section == 1) ? 6 : 2) . " questions out of " . numberToText(($section == 1) ? 8 : (($section == 2) ? 6 : 3)) . ":</td>";
    echo "<td>" . (($section == 1) ? 12 : 16) . "</td>"; // Set max marks as 12 for the first section, otherwise 16
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";

    // Loop through each question in the section
    $maxMarks = ($section == 1) ? 12 : 16; // Set max marks as 12 for the first section, otherwise 16
    for ($i = 1; $i <= ($section == 1 ? 8 : ($section == 2 ? 6 : 3)); $i++) {
        // Display the question row using the echoQuestionRow function
        echoQuestionRow($i, $dataRowCounter, $subjectId, $section, $maxMarks);
        $dataRowCounter++;
    }
}


?>

            </tbody>
        </table>

        <div id="questionsContainer">
                    <button class="print-button" onclick="window.print()">Print</button>

        </div>
       <script>
function generateQuestions(button) {
    var rowNumber = button.dataset.row;
    var coSelect = document.getElementById("coSelect_" + rowNumber);
    var coId = coSelect.value;
    var btId = document.getElementById("btSelect_" + rowNumber).value;
    var section = rowNumber; // Add this line to get the section information

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var questionTdElement = document.getElementById("generatedQuestions_" + rowNumber);
            var maxMarksTdElement = document.getElementById("maxMarks_" + rowNumber);

            if (questionTdElement && maxMarksTdElement) {
                // Parse the response as JSON
                var responseJson = JSON.parse(this.responseText);

                // Extract a random question text and marks from the response
                var randomQuestionIndex = Math.floor(Math.random() * responseJson.length);
                var questionText = responseJson[randomQuestionIndex].question_text;
                var marks = responseJson[randomQuestionIndex].marks;

                // Update the question and Max Marks columns in the same row
                questionTdElement.innerHTML = questionText;
                maxMarksTdElement.innerHTML = marks;
            }
        }
    };

    // Append the section information to the URL
    xhttp.open("GET", "get_questions.php?co_id=" + coId + "&bt_id=" + btId + "&section=" + section, true);
    xhttp.send();
}
</script>


        <?php
    } else {
        echo "Invalid schedule_id.";
    }

    // Close the database connection
    $conn->close();
    ?>

</body>

</html>
