<!DOCTYPE html>
<html>

<head>
    <style>
        /* Add your styling for the template management page here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 300px;
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .section-inputs {
            margin-top: 10px;
        }

        .section-input {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h2>Template Management</h2>

    <!-- Form to create and save a template -->
    <form method="post" action="save_template.php">
        <label>Template Name:</label>
        <input type="text" name="template_name" required>
        <br>

        <label>Number of Sections:</label>
        <input type="number" name="num_sections" id="numSections" required onchange="addSectionInputs()">
        <br>

        <div class="section-inputs" id="sectionInputsContainer"></div>

        <!-- Add other inputs for template customization -->

        <button type="submit">Save Template</button>
    </form>

    <script>
        function addSectionInputs() {
            var numSections = document.getElementById('numSections').value;
            var container = document.getElementById('sectionInputsContainer');
            container.innerHTML = ''; // Clear previous inputs

            for (var i = 1; i <= numSections; i++) {
                var label = document.createElement('label');
                label.textContent = 'Q' + i + ' - Total Rows:';
                container.appendChild(label);

                var input = document.createElement('input');
                input.type = 'number';
                input.name = 'total_rows_per_section[]';
                input.className = 'section-input';
                input.required = true;
                container.appendChild(input);

                var rowsToSolveLabel = document.createElement('label');
                rowsToSolveLabel.textContent = 'Q' + i + ' - Rows to Solve:';
                container.appendChild(rowsToSolveLabel);

                var rowsToSolveInput = document.createElement('input');
                rowsToSolveInput.type = 'number';
                rowsToSolveInput.name = 'rows_to_solve_per_section[]';
                rowsToSolveInput.className = 'section-input';
                rowsToSolveInput.required = true;
                container.appendChild(rowsToSolveInput);
            }
        }
    </script>

</body>

</html>
