<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matka API Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            width: 80%;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        input[type="button"], input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        input[type="button"]:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[disabled] {
            background-color: #aaa;
            cursor: not-allowed;
        }
        .tab {
            display: none;
        }
    </style>
    <!-- Include jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <form id="setupForm">
            <!-- Step 1: API Key and Domain Key Input -->
            <div class="tab">
                <h2>Matka API Setup</h2>
                <input type="text" id="domain_key" name="domain_key" placeholder="Enter Domain Key" required><br>
                <input type="text" id="api_key" name="api_key" placeholder="Enter API Key" required><br>
                <input type="button" id="nextButton" value="Next" onclick="updateEnvFile()" disabled>
            </div>

            <!-- Step 2: Database Connection Inputs -->
            <div class="tab">
                <h2>Enter Database Connections</h2>
                <input type="text" id="db_name" name="db_name" placeholder="DB Name" required><br>
                <input type="text" id="db_user" name="db_user" placeholder="DB User" required><br>
                <input type="password" id="db_password" name="db_password" placeholder="DB Password" required><br>
                <input type="button" value="Back" onclick="prevTab()">
                <input type="button" value="Next" onclick="updateConfigFile()">
            </div>

            <!-- Step 3: Final Submit -->
            <div class="tab">
                <h2>Final Submit</h2>
                <p>Once submit Check database</p>
                <input type="button" value="Back" onclick="prevTab()">
                <input type="button" value="Submit" onclick="finalSubmit()">
            </div>
        </form>
    </div>

    <script>
        let currentTab = 0; 
        showTab(currentTab);

        $(document).ready(function() {
            $('#domain_key, #api_key').on('input', function() {
                const domainKey = $('#domain_key').val().trim();
                const apiKey = $('#api_key').val().trim();
                if (domainKey && apiKey) {
                    $('#nextButton').prop('disabled', false);
                } else {
                    $('#nextButton').prop('disabled', true); 
                }
            });
        });

        function showTab(n) {
            let tabs = document.getElementsByClassName("tab");
            tabs[n].style.display = "block";
        }

        function nextTab() {
            let tabs = document.getElementsByClassName("tab");
            tabs[currentTab].style.display = "none";
            currentTab++;
            if (currentTab >= tabs.length) {
                return false;
            }
            showTab(currentTab);
        }

        function prevTab() {
            let tabs = document.getElementsByClassName("tab");
            tabs[currentTab].style.display = "none";
            currentTab--;
            showTab(currentTab);
        }

        function updateEnvFile() {
            const domainKey = $('#domain_key').val();
            const apiKey = $('#api_key').val();

            
            $.ajax({
                url: 'matka_setup.php', 
                type: 'POST',
                data: {
                    action: 'update_env',
                    domain_key: domainKey,
                    api_key: apiKey
                },
                success: function(response) {
                    alert('Environment file updated successfully.');
                    console.log(response); 
                    nextTab();
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while updating the environment file.');
                    console.error(xhr, status, error);
                }
            });
        }

        function updateConfigFile() {
            const dbName = $('#db_name').val();
            const dbUser = $('#db_user').val();
            const dbPassword = $('#db_password').val();

           
            $.ajax({
                url: 'matka_setup.php', 
                type: 'POST',
                data: {
                    action: 'update_config',
                    db_name: dbName,
                    db_user: dbUser,
                    db_password: dbPassword
                },
                success: function(response) {
                    alert('Database configuration file updated successfully.');
                    console.log(response); 
                    nextTab();
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while updating the configuration file.');
                    console.error(xhr, status, error);
                }
            });
        }

        function finalSubmit() {
            $.ajax({
                url: 'sql.php',
                type: 'GET',
                success: function(response) {
                    alert(response); 
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while creating the tables.');
                    console.error(xhr, status, error);
                }
            });
        }
    </script>
</body>
</html>