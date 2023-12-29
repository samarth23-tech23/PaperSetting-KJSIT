<!DOCTYPE html>
<html>
<head>
    <title>Manage CO and BT Levels</title>
    <style>
        table {
            border-collapse: collapse;
            width: %;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Manage CO and BT Levels</h1>
    
    <!-- Form to add new CO level -->
    <form action="process_co_bt.php" method="post">
        <h2>Add New CO Level</h2>
        <label for="co_number">CO Number:</label>
        <input type="text" name="co_number" required>
        <label for="co_description">CO Description:</label>
        <input type="text" name="co_description" required>
        <input type="hidden" name="subject_id" value="<?php echo $_GET['subject_id']; ?>">
        <input type="submit" value="Add CO">
    </form>
    
    <!-- Form to add new BT level -->
    <form action="process_co_bt.php" method="post">
        <h2>Add New BT Level</h2>
        <label for="bt_level">BT Level:</label>
        <input type="text" name="bt_level" required>
        <label for="bt_description">BT Description:</label>
        <input type="text" name="bt_description" required>
        <input type="hidden" name="subject_id" value="<?php echo $_GET['subject_id']; ?>">
        <input type="submit" value="Add BT">
    </form>

    <!-- Display existing CO levels for the subject -->
    <h2>Existing CO Levels for Subject</h2>
    <table>
        <thead>
            <tr>
                <th>CO Number</th>
                <th>CO Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            $subject_id = $_GET['subject_id'];

            $co_query = "SELECT co_number, co_description FROM cos 
                         WHERE co_id IN (
                            SELECT co_id FROM subject_co_mapping WHERE subject_id = $subject_id
                         )";
            $co_result = $mysqli->query($co_query);

            if ($co_result) {
                if ($co_result->num_rows > 0) {
                    while ($row = $co_result->fetch_assoc()) {
                        echo "<tr><td>" . $row["co_number"] . "</td><td>" . $row["co_description"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No existing CO levels for this subject.</td></tr>";
                }
            } else {
                echo "CO Query Error: " . $mysqli->error;
            }
            $mysqli->close();
            ?>
        </tbody>
    </table>

    <!-- Display existing BT levels for the subject -->
    <h2>Existing BT Levels for Subject</h2>
    <table>
        <thead>
            <tr>
                <th>BT Level</th>
                <th>BT Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            $bt_query = "SELECT bt_level, bt_description FROM bts 
                         WHERE bt_id IN (
                            SELECT bt_id FROM subject_bt_mapping WHERE subject_id = $subject_id
                         )";
            $bt_result = $mysqli->query($bt_query);

            if ($bt_result) {
                if ($bt_result->num_rows > 0) {
                    while ($row = $bt_result->fetch_assoc()) {
                        echo "<tr><td>" . $row["bt_level"] . "</td><td>" . $row["bt_description"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No existing BT levels for this subject.</td></tr>";
                }
            } else {
                echo "BT Query Error: " . $mysqli->error;
            }
            $mysqli->close();
            ?>
        </tbody>
    </table>
</body>
</html>
