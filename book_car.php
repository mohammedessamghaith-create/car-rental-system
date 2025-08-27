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
    $plate = trim($_POST["plate"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);

    if (empty($plate) || empty($name) || empty($email) || empty($phone) || empty($address) || empty($start_date) || empty($end_date)) {
        $message = "Please fill all fields.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO bookings (PlateID, name, email, phone, address, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $plate, $name, $email, $phone, $address, $start_date, $end_date);

        if ($stmt->execute()) {
            $message = "Car booked successfully!";
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
    <title>Book a Car</title>
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
        <a href="register_car.php">Register Car</a>
         <a href="add_payment.php">Payment</a>
         <a href="book_car.php">Book Car</a>
         <a href="search_car.php">Search Car</a>
        </nav>
    </div>

    <section>
        <h1>Book a Car</h1>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="book_car.php" method="POST">
            <label for="plate">Plate ID:</label>
            <input type="text" id="plate" name="plate" placeholder="Enter car plate ID" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="address">Address:</label>
             <input type="text" id="address" name="address" placeholder="Enter your address" required>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="submit">Book Car</button>
        </form>
    </section>
</body>

</html>