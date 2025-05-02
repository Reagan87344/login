<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: blueviolet;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #form {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        #form h1 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            float: left;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        #login {
            width: 100%;
            color: white;
            background-color: blueviolet;
            padding: 10px;
            font-size: large;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #login:hover {
            background-color: indigo;
        }

        .message {
            font-size: 20px;
            margin-bottom: 15px;
        }

        p {
            margin-top: 15px;
            color: #666;
        }

        a {
            color: blueviolet;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once "database.php";

    $success_message = "";
    $error_message = "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $errors = array();

        if (empty($username) || empty($password)) {
            array_push($errors, "Both fields are required");
        }

        if (empty($errors)) {
            $sql = "SELECT * FROM viewers WHERE username = ?";
            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $user = mysqli_fetch_assoc($result);

                    if (password_verify($password, $user['password'])) {
                        $success_message = "Login successful!!!";
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['id'] = $user['id'];
                        // header("Location: dashboard.php"); exit();
                    } else {
                        $error_message = "Incorrect password!";
                    }
                } else {
                    $error_message = "No user found with that username.";
                }
            } else {
                $error_message = "Database error: could not prepare statement.";
            }
        } else {
            $error_message = implode("<br>", $errors);
        }
    }
    ?>

    <div id="form">
        <h1>Laracasts</h1>

        <?php if (!empty($success_message)): ?>
            <p class="message" style="color:green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="message" style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" id="login" name="submit" value="Login">
        </form>

        <p>Don't have an account? <a href="reg.php">Register</a></p>
    </div>
</body>
</html>
