<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-size: 17px;
        }

        .header-box {
            background-color: #f8f8f8;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .header-text {
            font-size: 22px;
            font-weight: bold;
        }

        .header-details {
            font-size: 17px;
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

        th, td {
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
            background-color: #B22222;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #700303;
        }

        p {
            font-weight: bold;
            font-size: 21px;
            text-align: center;
            margin-bottom: 2px;
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
        5 => 'five',
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
    $sql = "SELECT es.schedule_id, es.academic_year, d.department_name, es.test, es.year, es.semester, es.course_code, es.marks, es.exam_date, es.status, s.subject_name
    FROM exam_schedule_tt es
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


// Function to fetch subject_id based on the provided course_code
function getSubjectIdByCourseCode($conn, $courseCode)
{
    $sql = "SELECT subject_id FROM subjects WHERE course_code = '$courseCode'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['subject_id'];
    }

    return null;
}

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

// Fetch data from the database based on schedule_id
$scheduleId = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : null;

if ($scheduleId !== null) {
    $sql = "SELECT * FROM exam_schedule_tt WHERE schedule_id = $scheduleId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}

if ($courseCode !== null) {
    $subjectSql = "SELECT subject_name FROM subjects WHERE course_code = '$courseCode'";
    $subjectResult = $conn->query($subjectSql);

    if ($subjectResult->num_rows > 0) {
        $subjectRow = $subjectResult->fetch_assoc();
        $subjectName = $subjectRow['subject_name'];
    }
}

// Fetch subject_id based on the provided course_code
$subjectId = getSubjectIdByCourseCode($conn, $courseCode);

// Fetch COs for the subject
$cos = getCOsFromDB($conn, $subjectId);

// Fetch BT Levels for the subject
$btLevels = getBTLevelsFromDB($conn, $subjectId);

// Close the connection
$conn->close();
?>

<div class="header-box">
    <div class="header-text">K. J. Somaiya Institute of Technology, Sion, Mumbai-22</div>
    <div class="header-details">(Autonomous College Affiliated to University of Mumbai)</div>
</div>

<p>Academic Year <?php echo $row['academic_year']; ?><br> Department of Information Technology<br>Term Test - <?php echo $row['test']; ?></p>

<table class="table table-bordered">
    <tr>
        <td style="text-align: left; font-weight: bold;">Class: <?php echo $row['year']; ?></td>
        <td style="text-align: left; font-weight: bold;">Semester: <?php echo $row['semester']; ?></td>
        <td style="text-align: left; font-weight: bold;">Date: <?php echo $row['exam_date']; ?></td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: left; font-weight: bold;">Course Name: <?php echo $subjectName; ?></td>
        <td style="text-align: left; font-weight: bold;">Marks: <?php echo $row['marks']; ?></td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: left; font-weight: bold;">Course Code: <?php echo $row['course_code']; ?></td>
        <td style="text-align: left; font-weight: bold;">Time: <?php echo $row['exam_date']; ?></td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: left; font-weight: bold;">All Questions are compulsory</td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th>Q.No</th>
        <th>Sub Questions</th>
        <th>Solve questions:</th>
        <th>Max Marks</th>
        <th>CO</th>
        <th>BT Level</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="2">Q.1</td>
        <td>a) </td>
        <td></td>
        <td></td>
        <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <button>Generate</button>
        </td>
    </tr>
    <tr>
        <td>b)</td>
        <td></td>
        <td></td>
        <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <button>Generate</button>
        </td>
    </tr>

    <!-- Repeat this row structure for other questions -->



    <tr>
                
                <td rowspan="3">Q.2</td>
                <td>a) </td>
                <td> </td>
                <td> </td>
                <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td><button>Generate</button></td>
               
            </tr>
            <tr>
                
            
                <td></td>
                <td>OR</td>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
              
            </tr>

            <tr>
                <td>b)</td>
                <td></td>
                <td></td>
                <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td>
                    <button>Generate</button>
                </td>
            </tr>


            <!-- Q.3  -->
            <tr>
                
                <td rowspan="3">Q.3</td>
                <td>a) </td>
                <td> </td>
                <td> </td>
                <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td><button>Generate</button></td>
               
            </tr>
            <tr>
                
            
                <td></td>
                <td>OR</td>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
              
            </tr>

            <tr>
                <td>b)</td>
                <td></td>
                <td></td>
                <td>
            <select name='co[]' id='coSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select CO</option>
                <?php
                if ($cos === null || empty($cos)) {
                    echo "<option value='' disabled>No COs found</option>";
                } else {
                    foreach ($cos as $co) {
                        echo "<option value='" . $co['co_id'] . "'>" . $co['co_number'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td>
            <select name='bt_level[]' id='btSelect_<?php echo $dataRowNumber; ?>'>
                <option value='' disabled>Select BT Level</option>
                <?php
                if ($btLevels === null || empty($btLevels)) {
                    echo "<option value='' disabled>No BT Levels found</option>";
                } else {
                    foreach ($btLevels as $btLevel) {
                        echo "<option value='" . $btLevel['bt_id'] . "'>" . $btLevel['bt_description'] . "</option>";
                    }
                }
                ?>
            </select>
        </td>
                <td>
                    <button>Generate</button>
                </td>
            </tr>
    




            
    
     
    


    </tbody>
</table>
</body>
</html>
