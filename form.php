<!DOCTYPE html>
<html>
<head>
    <title>Question Entry Form</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Question Entry Form</h1>
    <form action="processform.php" method="post">
        <table>
            <tr>
                <td><label for="subject_id">Select Subject:</label></td>
                <td>
                    <select name="subject_id" id="subject_id">
                        <!-- Populate this dropdown with subjects from your database -->
                        <?php
                        // Connect to your database and fetch subjects
                        $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);

                        if ($mysqli->connect_error) {
                            die("Connection failed: " . $mysqli->connect_error);
                        }

                        $query = "SELECT subject_id, subject_name FROM Subjects";
                        $result = $mysqli->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $subject_id = $row['subject_id'];
                                $subject_name = $row['subject_name'];
                                echo "<option value='$subject_id'>$subject_name</option>";
                            }
                        }

                        $mysqli->close();
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Submit">
    </form>
    <button id="addRow">Add Row</button>

    <table id="questionTable">
        <tr>
            <th>Question Text</th>
            <th>Image Path</th>
            <th>Marks</th>
            <th>CO Number</th>
            <th>BT Level</th>
        </tr>
    </table>

    <script>
        // Function to add a new row for another question
        function addQuestionRow() {
            const table = document.querySelector("#questionTable");
            const newRow = table.insertRow(table.rows.length);

            // Question Text input
            const cellQuestionText = newRow.insertCell(0);
            const inputQuestionText = document.createElement("textarea");
            inputQuestionText.rows = 4;
            inputQuestionText.cols = 50;
            inputQuestionText.name = "question_text[]";
            cellQuestionText.appendChild(inputQuestionText);

            // Image Path input
            const cellImagePath = newRow.insertCell(1);
            const inputImagePath = document.createElement("input");
            inputImagePath.type = "text";
            inputImagePath.name = "image_path[]";
            cellImagePath.appendChild(inputImagePath);

            // Marks input
            const cellMarks = newRow.insertCell(2);
            const inputMarks = document.createElement("input");
            inputMarks.type = "number";
            inputMarks.name = "marks[]";
            cellMarks.appendChild(inputMarks);

            // CO Number select
            const cellCoNumber = newRow.insertCell(3);
            const selectCoNumber = document.createElement("select");
            selectCoNumber.name = "co_number[]";
            // Add logic to populate this dynamically based on the selected subject
            cellCoNumber.appendChild(selectCoNumber);

            // BT Level select
            const cellBtLevel = newRow.insertCell(4);
            const selectBtLevel = document.createElement("select");
            selectBtLevel.name = "bt_level[]";
            selectBtLevel.innerHTML = `
                <option value="BT1">BT1</option>
                <option value="BT2">BT2</option>
                <!-- Add more BT options as needed -->
            `;
            cellBtLevel.appendChild(selectBtLevel);

            // Fetch CO values based on the selected subject and populate the CO Number dropdown
            const subjectDropdown = document.getElementById("subject_id");
            const selectedSubject = subjectDropdown.value;
            const coDropdown = selectCoNumber;
            fetch(`get_cos.php?subject_id=${selectedSubject}`)
                .then(response => response.json())
                .then(data => {
                    for (const coNumber in data) {
                        if (data.hasOwnProperty(coNumber)) {
                            var coValue = data[coNumber];
                            var option = document.createElement("option");
                            option.value = coValue;
                            option.text = `${coNumber} - ${coValue}`;
                            coDropdown.appendChild(option);
                        }
                    }
                });
        }

        // Add an event listener to the "Add Row" button
        document.getElementById("addRow").addEventListener("click", addQuestionRow);
    </script>

    <script>
        // JavaScript to fetch CO values based on the selected subject
        document.getElementById("subject_id").addEventListener("change", function() {
            var selectedSubject = this.value;
            var coDropdown = document.querySelector("#questionTable select[name='co_number[]']");
            coDropdown.innerHTML = ''; // Clear existing options

            // Use AJAX to retrieve CO values from the server and populate the dropdown
            fetch(`get_cos.php?subject_id=${selectedSubject}`)
                .then(response => response.json())
                .then(data => {
                    for (const coNumber in data) {
                        if (data.hasOwnProperty(coNumber)) {
                            var coValue = data[coNumber];
                            var option = document.createElement("option");
                            option.value = coValue;
                            option.text = `${coNumber} - ${coValue}`;
                            coDropdown.appendChild(option);
                        }
                    }
                });
        });
    </script>
</body>
</html>
