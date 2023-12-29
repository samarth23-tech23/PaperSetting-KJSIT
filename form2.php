<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions to Subject</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        h1 {
            color: #FFFFFF;
        }
        h2{
            color: #333;
        }
        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 15px;
            background-color: #f8f8f8;
            padding: 12px;
            border-radius: 4px;
        }

        .addquestion,
        .existquestion {
            margin-bottom: 20px;
        }

        .addquestion {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .existquestion {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header>
        <h1> Question Bank</h1>
    </header>

    <main>
        <?php
        $subject_id = $_GET['subject_id'];

        $mysqli = new mysqli("localhost", "root", "", "kjsit", 3307);
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $subject_query = "SELECT subject_name FROM subjects WHERE subject_id = $subject_id";
        $subject_result = $mysqli->query($subject_query);
        if ($subject_result->num_rows > 0) {
            $row = $subject_result->fetch_assoc();
            $subject_name = $row['subject_name'];
            echo "<h2>Subject: $subject_name</h2>";
        } else {
            echo "Subject not found";
        }
        ?>

        <div class="addquestion">
            <section class="add-new-question">
                <h2>Add New Question</h2>
                <form action="process_add_question.php" method="post" id="addQuestionForm">
                    <label for="question_text">Question Text:</label>
                    <input type="text" name="question_text" autocomplete="off" required >

                    <label for="image_path">Image Path:</label>
                    <input type="text" name="image_path">

                    <label for="marks">Marks:</label>
                    <input type="number" name="marks" required>

                    <label for="co_level">Select CO Level:</label>
        <select name="co_id" id="co_level">
            <?php
            $co_query = "SELECT co_id, co_number, co_description FROM cos 
                         WHERE co_id IN (
                            SELECT co_id FROM subject_co_mapping WHERE subject_id = $subject_id
                         )";
            $co_result = $mysqli->query($co_query);

            if ($co_result->num_rows > 0) {
                while ($co_row = $co_result->fetch_assoc()) {
                    echo "<option value='" . $co_row['co_id'] . "'>" . $co_row['co_number'] . " - " . $co_row['co_description'] . "</option>";
                }
            } else {
                echo "<option value=''>No CO levels found</option>";
            }
            ?>
        </select>
                    <label for="bt_level">Select BT Level:</label>
        <select name="bt_id" id="bt_level">
            <?php
            $bt_query = "SELECT bt_id, bt_level, bt_description FROM bts 
                         WHERE bt_id IN (
                            SELECT bt_id FROM subject_bt_mapping WHERE subject_id = $subject_id
                         )";
            $bt_result = $mysqli->query($bt_query);

            if ($bt_result->num_rows > 0) {
                while ($bt_row = $bt_result->fetch_assoc()) {
                    echo "<option value='" . $bt_row['bt_id'] . "'>" . $bt_row['bt_level'] . " - " . $bt_row['bt_description'] . "</option>";
                }
            } else {
                echo "<option value=''>No BT levels found</option>";
            }
            ?>
        </select>

                    <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                    <input type="submit" value="Add Question">
                </form>
            </section>
        </div>

        <div class="existquestion">
            <section class="existing-questions">
                <?php
                $questions_query = "SELECT * FROM questions WHERE subject_id = $subject_id";
                $questions_result = $mysqli->query($questions_query);

                if ($questions_result->num_rows > 0) {
                    echo "<h2>Existing Questions for $subject_name:</h2>";
                    echo "<ul>";
                    $counter = 1;
                    while ($row = $questions_result->fetch_assoc()) {
                        echo "<li><strong>Question $counter:</strong> " . $row['question_text'] . "</li>";
                        $counter++;
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No existing questions for this subject.</p>";
                }
                ?>
            </section>
        </div>

        <?php
        $mysqli->close();
        ?>
    </main>
</body>
</html>