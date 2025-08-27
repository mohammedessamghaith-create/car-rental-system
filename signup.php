<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carsproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);
    $role = trim($_POST["role"]);


    if (empty($username) || empty($password) || empty($confirmPassword) || empty($role)) {
        $message = "Please fill all fields.";
        $message_type = "error";
    } else if ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $message_type = "error";
    }
     else {
          // Hash the password using bcrypt before saving
         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // SQL to insert new user
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            $message = "Signup successful! You can now login.";
             $message_type = "success";
        } else {
            $message = "Error: " . $stmt->error;
             $message_type = "error";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            color: #2c3e50;
            line-height: 1.6;
            padding: 0 20px;
        }
        #background-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1; /* Place behind other content */
            object-fit: cover;
        }

        .navbar {
            background-color: #34495e;
            background-image: url(3.jpeg);          
            background-size: cover; 
            background-repeat: no-repeat;  color: #2c3e50;
            color: #ecf0f1;
            padding: 15px 0;
            display: flex; /* Enable flexbox */
            justify-content: space-between; /* Distribute space between elements */
            align-items: center; /* Center items vertically */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 70px;
            padding: 15px 30px; /* Add padding on both sides */
        }
        .navbar .logo-container {
            display: flex; /* جعل الشعار والعنوان بجانب بعضهما */
            align-items: center; /* توسيط العناصر عموديًا */

        }    

        .logo {
            height: 75px; 
            margin-right: 20px; 
        }
         
        .navbar h2 {
            margin: 0;
            font-size: 30px;
        }

        .navbar nav {
            display: flex;
        }

        .navbar nav a {
            color: #ecf0f1;
            font-weight: 600;
            margin: 0 15px;
            text-decoration: none;
            font-size: 19px;
        }

        .navbar nav a:hover {
            color: #1abc9c;
        }

        section {
            margin: 30px auto;
            max-width: 500px;
            border-style:solid;
            background-color: #ffffff;
            background-image: url(3.jpeg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 35px;
            text-align: center;
            margin-bottom: 20px;
            color:rgb(255, 255, 255);
        }

        form {
            display: flex;
            flex-direction: column;
            color:rgb(48, 83, 92);
            gap: 15px;
        }

        input, select {
            padding: 12px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 14px;
            color: #34495e;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #1abc9c;
            outline: none;
        }

        button {
            background-color: #1abc9c;
            color: #ffffff;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 21px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #16a085;
        }

        .message {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #e74c3c;
            color: #ffffff;
        }

        .success {
            background-color: #2ecc71;
            color: #ffffff;
        }
    </style>
</head>
<body>
<video id="background-video" autoplay loop muted playsinline>
        <source src="v6.mp4" type="video/mp4">
    </video>
    <div class="navbar">
    <div class="logo-container">
        <img src="4.png" alt="Logo" class="logo">
        <h2>Car Rental System</h2>
        </div>
        <nav>
        <a href="login.php">Login</a>
        </nav>
    </div>

    <section>
        <h1>Signup</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
           <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

           <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>


            <label for="role">Role:</label>
            <select id="role" name="role" required>
               <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select>
            <button type="submit">Signup</button>
        </form>
    </section>
</body>
</html>